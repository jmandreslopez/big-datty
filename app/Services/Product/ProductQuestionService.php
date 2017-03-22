<?php namespace App\Services\Product;

use App\Helpers\SnsClient;
use Symfony\Component\DomCrawler\Crawler;
use App\Helpers\Amazon\ScraperHelper;
use App\Models\Product\Product;
use App\Models\Product\ProductQuestion;
use App\Models\Product\ProductQuestionMetadata;

class ProductQuestionService
{
    /**
     * Get Questions URL
     *
     * @param Product $product
     * @param int $page
     * @return string
     */
    public static function parentUrl($product, $page)
    {
        $domain = $product->account->marketplace->domain;
        $base = 'http://' . $domain . '/ask/questions/asin/';
        $params = [
            'sort' => 'SUBMIT_DATE',
        ];

        return $base . $product->source_id . '/' . $page . '/?' . http_build_query($params);
    }

    /**
     * Get Question URL
     *
     * @param Product $product
     * @param int $id
     * @return string
     */
    public static function url($product, $id)
    {
        $domain = $product->account->marketplace->domain;
        $base = 'http://' . $domain . '/forum/-/';
        $params = [
            'asin' => $product->source_id,
        ];

        return $base . $id . '/?' . http_build_query($params);
    }

    /**
     * Create Question
     *
     * @param Product $product
     * @param ScraperHelper $scraper
     * @param int $sourceId
     * @param Crawler $node
     * @return ProductQuestion|bool
     */
    public static function create($product, $scraper, $sourceId, $node)
    {
        $productQuestion = ProductQuestion::whereSourceId($sourceId)->first();

        if (empty($productQuestion)) {

            // Date
            $raw = $node->filter('.cdAuthorInfoBlock')->text();

            if (empty($raw)) {
                debug('Error!', 'error');
            }

            preg_match($scraper::QUESTION_DATE_REGEX, $raw, $date);
            $date = $scraper->getDate($date, $scraper);

            if ($date->diffInDays() <= config('bigdatty.products.questions.length')) {
                $productQuestion = new ProductQuestion([
                    'product_id' => $product->id,
                    'source_id'  => $sourceId,
                    'date'       => $date->toDateString(),
                ]);

                $productQuestion->save();

                debug('Question created: ' . $productQuestion->id);

                self::metadata($productQuestion, $scraper, $node);

                // Publish Product
                SnsClient::question('new', $productQuestion->id);

                return $productQuestion;
            }
            else {
                return false;
            }
        }

        return false;
    }

    /**
     * @param ProductQuestion $productQuestion
     * @param Crawler $node
     */
    protected static function metadata($productQuestion, $scraper, $node)
    {
        $data = [];

        // Title
        $title = $node->filter('.cdQuestionText')->text();
        if (! empty($title)) {
            $data[] = [
                'product_question_id' => $productQuestion->id,
                'meta_key'            => 'title',
                'meta_value'          => trim($title),
            ];
        }

        // Author
        $raw = $node->filter('.cdAuthorInfoBlock')->text();
        preg_match($scraper::QUESTION_DATE_REGEX, $raw, $date);
        if (! empty($date)) {
            $data[] = [
                'product_question_id' => $productQuestion->id,
                'meta_key'            => 'author',
                'meta_value'          => trim($date['author']),
            ];
        }

        // URL
        $url = self::url($productQuestion->product, $productQuestion->source_id);
        $data[] = [
            'product_question_id' => $productQuestion->id,
            'meta_key'            => 'url',
            'meta_value'          => $url,
        ];

        if (! empty($data)) {
            ProductQuestionMetadata::insert($data);
        }
    }
}