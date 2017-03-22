<?php namespace App\Jobs\Amazon\Product;

use App\Jobs\BaseScraperJob;
use App\Helpers\Amazon\ScraperHelper;
use App\Models\Product\Product;
use App\Models\Product\ProductReview;
use App\Services\Account\AccountService;
use App\Services\Product\ProductReviewService;
use App\Services\Product\ProductService;
use Symfony\Component\DomCrawler\Crawler;
use Carbon\Carbon;

class ProductReviewJob extends BaseScraperJob
{
    /**
     * @var Product
     */
    protected $product;

    /**
     * @var ProductReview
     */
    protected $review;

    /**
     * Initialize Job
     */
    protected function init()
    {
        $this->product = $this->attributes['product'];

        $this->scraper = ScraperHelper::getScraper(AccountService::country($this->account));
    }

    /**
     * Process Job
     */
    protected function process()
    {
        $this->getReviews();
    }

    /**
     * Get Reviews
     *
     * @param int $page
     */
    protected function getReviews($page = 1)
    {
        // Request
        $this->getCrawler(ProductReviewService::url($this->product, $page));

        $total = $this->getTotal();
        if ($total !== false) {
            if ($total != $this->product->total_reviews) {

                // Get Review
                $this->crawler->filter('#cm_cr-review_list > .a-section.review')->each(function (Crawler $node) {
                    $this->review = ProductReviewService::create($this->product, $node);
                });

                // Request next page if needed
                if ($this->nextPage() === true) {
                    $this->getReviews($page + 1);
                }

                // Update total Reviews
                ProductService::updateReviews($this->product, $total);
            }
        }
    }

    /**
     * Get Reviews total
     *
     * @return bool|string
     */
    protected function getTotal()
    {
        $show = $this->crawler->filter('.totalReviewCount');

        if (!empty($show) && $show->count() > 0) {
            $total = $show->text();
            debug('Total: ' . $total);

            return $total;
        }

        return false;
    }

    /**
     * Check if next page is needed
     *
     * @return bool
     */
    protected function nextPage()
    {
        if (! empty($this->review)) {
            $diff = Carbon::parse($this->review->date)->diffInDays();
            $days = config('bigdatty.products.reviews.length');

            if ($diff <= $days) {
                return true;
            }
        }

        return false;
    }
}