<?php namespace App\Services\Product;

use App\Helpers\SnsClient;
use App\Services\Account\AccountService;
use Symfony\Component\DomCrawler\Crawler;
use App\Helpers\Amazon\ScraperHelper;
use App\Models\Product\Product;
use App\Models\Product\ProductReview;
use App\Models\Product\ProductReviewMetadata;

class ProductReviewService
{
    /**
     * Get Review URL
     *
     * @param Product $product
     * @param int $page
     * @return string
     */
    public static function url($product, $page)
    {
        $domain = $product->account->marketplace->domain;
        $base = 'http://' . $domain . '/product-reviews/';
        $params = [
            'sortBy' 	   => 'recent',
            'reviewerType' => 'all_reviews',
            'filterByStar' => 'all_stars',
            'pageNumber'   => $page
        ];

        return $base . $product->source_id . '?' . http_build_query($params);
    }

    /**
     * Create ProductReview
     *
     * @param Product $product
     * @param Crawler $node
     * @return ProductReview
     */
    public static function create($product, $node)
    {
        // Review Id
        $sourceId = $node->attr('id');

        $productReview = ProductReview::whereSourceId($sourceId)->first();

        if (empty($productReview)) {
            $country = AccountService::country($product->account);
            $scraper = ScraperHelper::getScraper($country);

            // Stars
            $class = $node->filter('.review-rating')->attr('class');
            $quantity = strpos($class, 'a-star-');
            $stars = substr($class, $quantity + 7, 1);

            // Date
            $raw = $node->filter('.review-date')->text();
            preg_match($scraper::REVIEW_DATE_REGEX, $raw, $date);
            $date = $scraper->getDate($date, $scraper);

            if ($date->diffInDays() <= config('bigdatty.products.reviews.length')) {

                // Create ProductReview
                $productReview = new ProductReview([
                    'product_id' => $product->id,
                    'source_id'  => $sourceId,
                    'stars'      => $stars,
                    'date'       => $date->toDateString(),
                ]);

                // Save ProductReview
                $productReview->save();

                debug('Review created: ' . $productReview->id);

                self::metadata($productReview, $node);

                // Publish Product
                SnsClient::review('new', $productReview->id);

                return $productReview;
            }
            else {
                return false;
            }
        }

        return false;
    }

    /**
     * Create ProductReviewMetadata
     *
     * @param ProductReview $productReview
     * @param Crawler $node
     */
    protected static function metadata($productReview, $node)
    {
        $data = [];

        // Title
        $title = $node->filter('.review-title')->text();
        if (! empty($title)) {
            $data[] = [
                'product_review_id'  => $productReview->id,
                'meta_key'           => 'title',
                'meta_value'         => trim($title),
            ];
        }

        // Author
        $author = $node->filter('.author')->text();
        if (! empty($author)) {
            $data[] = [
                'product_review_id'  => $productReview->id,
                'meta_key'           => 'author',
                'meta_value'         => trim($author),
            ];
        }

        // Content
        $content = $node->filter('.review-text')->text();
        if (! empty($content)) {
            $data[] = [
                'product_review_id'  => $productReview->id,
                'meta_key'           => 'content',
                'meta_value'         => trim($content),
            ];
        }

        // Url
        $domain = $productReview->product->account->marketplace->domain;
        $base = 'http://' . $domain;
        $url = $base . $node->filter('.review-title')->attr('href');
        if (! empty($url)) {
            $data[] = [
                'product_review_id'  => $productReview->id,
                'meta_key'           => 'url',
                'meta_value'         => $url,
            ];
        }

        // Save ProductMetadata
        if (! empty($data)) {
            ProductReviewMetadata::insert($data);
        }
    }
}