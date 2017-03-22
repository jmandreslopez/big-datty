<?php namespace App\Jobs\Amazon\Product;

use App\Jobs\BaseScraperJob;
use App\Helpers\Amazon\ScraperHelper;
use App\Models\Product\Product;
use App\Models\Product\ProductQuestion;
use App\Services\Account\AccountService;
use App\Services\Product\ProductQuestionService;
use App\Services\Product\ProductService;
use Symfony\Component\DomCrawler\Crawler;
use Carbon\Carbon;

class ProductQuestionJob extends BaseScraperJob
{
    /**
     * @var Product
     */
    protected $product;

    /**
     * @var ProductQuestion
     */
    protected $question;

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
        $this->getQuestions();
    }

    /**
     * Get Questions page
     *
     * @param int $page
     */
    protected function getQuestions($page = 1)
    {
        $this->getCrawler(ProductQuestionService::parentUrl($this->product, $page));

        $total = $this->getTotal();
        if ($total !== false) {
            if ($total != $this->product->total_questions) {
                $questionIds = $this->crawler->filter('.askTeaserQuestions > .a-spacing-base > .a-fixed-left-grid-inner > div.a-col-right')->each(function (Crawler $node) {
                    $scraper = $this->scraper;
                    preg_match($scraper::QUESTION_ID_REGEX, $node->html(), $matches);

                    return $matches['id'];
                });

                // Request each Question
                foreach ($questionIds as $questionId) {
                    $this->question = $this->getQuestion($questionId);
                }

                // Request next page if needed
                if ($this->nextPage() === true) {
                    $this->getQuestions($page + 1);
                }

                // Update total Questions
                ProductService::updateQuestions($this->product, $total);
            }
        }
    }

    /**
     * Get Question
     *
     * @param int $questionId
     * @return array|bool
     */
    protected function getQuestion($questionId)
    {
        // Request Question
        $this->getCrawler(ProductQuestionService::url($this->product, $questionId));

        $node = $this->crawler->filter('#prod-right');
        if (! empty($node) && $node->count() > 0) {
            return ProductQuestionService::create($this->product, $this->scraper, $questionId, $node);
        }

        return false;
    }

    /**
     * Get Questions total
     *
     * @return bool|string
     */
    protected function getTotal()
    {
        $show = $this->crawler->filter('.askPaginationHeaderMessage');
        if (!empty($show) && $show->count() > 0) {
            $scraper = $this->scraper;
            preg_match($scraper::QUESTION_TOTAL_REGEX, $show->text(), $matches);
            if (! empty($matches)) {
                $total = $matches['total'];
                debug('Total: ' . $total);

                return $total;
            }
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
        if (! empty($this->question)) {
            $diff = Carbon::parse($this->question->date)->diffInDays();
            $days = config('bigdatty.products.reviews.length');

            if ($diff <= $days) {
                return true;
            }
        }

        return false;
    }
}