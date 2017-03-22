<?php namespace App\Traits;

use App\Jobs\Amazon\Report\ReportJob;
use Laravel\Lumen\Routing\DispatchesJobs;
use App\Models\Account\Account;
use App\Models\Order\Order;
use App\Models\Product\Product;
use App\Models\Report\Report;
use App\Jobs\Amazon\NextTokenJob;
use App\Jobs\Amazon\Feedback\FeedbackJob;
use App\Jobs\Amazon\Order\OrderJob;
use App\Jobs\Amazon\Order\OrderPendingJob;
use App\Jobs\Amazon\Order\OrderReportJob;
use App\Jobs\Amazon\Product\ProductJob;
use App\Jobs\Amazon\Product\ProductReviewJob;
use App\Jobs\Amazon\Product\ProductQuestionJob;

trait QueueTrait
{
    use DispatchesJobs;

    /**
     * Queue NextToken request
     *
     * @param Account $account
     * @param string $action
     * @param string $token
     * @param array $options
     */
    protected function queueNextToken($account, $action, $token, $options)
    {
        $attributes = [
            'account' => $account,
            'action'  => $action,
            'token'   => $token,
            'options' => json_encode($options),
        ];

        // Queue Request
        $job = (new NextTokenJob($attributes))
            ->onQueue(config('bigdatty.amazon.sqs.next-tokens'));

        // Dispatch Job
        $this->dispatch($job);

        debug('NextToken Queued');
    }

    /**
     * Queue Order creation
     *
     * @param Account $account
     * @param \SimpleXMLElement $item
     * @param array $options
     */
    protected function queueOrder($account, $item, $options)
    {
        $attributes = [
            'account' => $account,
            'item'    => json_encode($item),
            'options' => json_encode($options),
        ];

        // Queue Order
        $job = (new OrderJob($attributes))
            ->onQueue(config('bigdatty.amazon.sqs.orders'));

        $this->dispatch($job);

        debug('Order Queued');
    }

    /**
     * Queue Orders with pending status
     *
     * @param Account $account
     * @param Order $order
     * @param array $options
     */
    protected function queueOrderPending($account, $order, $options)
    {
        $attributes = [
            'account' => $account,
            'order'   => $order,
            'options' => json_encode($options),
        ];

        // Queue Pending Order
        $job = (new OrderPendingJob($attributes))
            ->onQueue(config('bigdatty.amazon.sqs.orders-pending'))
            ->delay(config('bigdatty.orders.pending.delay'));

        $this->dispatch($job);

        debug('OrderPending Queued');
    }

    /**
     * Queue Orders needed for a Feedback
     *
     * @param Account $account
     * @param array $items
     * @param array $options
     */
    protected function queueFeedback($account, $items, $options)
    {
        $attributes = [
            'account' => $account,
            'items'   => json_encode($items),
            'options' => json_encode($options),
        ];

        // Queue Pending Order
        $job = (new FeedbackJob($attributes))
            ->onQueue(config('bigdatty.amazon.sqs.feedbacks'));

        $this->dispatch($job);

        debug('Feedback Queued');
    }

    /**
     * Queue Orders from a Report
     *
     * @param Account $account
     * @param array $item
     */
    protected function queueOrderReport($account, $item)
    {
        $attributes = [
            'account' => $account,
            'item'    => json_encode($item),
        ];

        // Queue ReportOrder
        $job = (new OrderReportJob($attributes))
            ->onQueue('order-report');

        $this->dispatch($job);

        debug('OrderReport Queued');
    }

    /**
     * Queue Product creation
     *
     * @param Account $account
     * @param Order $order
     * @param array $item
     */
    protected function queueProduct($account, $order, $item)
    {
        $attributes = [
            'account' => $account,
            'order'   => $order,
            'item'    => json_encode($item),
        ];

        // Queue Product
        $job = (new ProductJob($attributes))
            ->onQueue(config('bigdatty.amazon.sqs.products'));

        $this->dispatch($job);

        debug('Product Queued');
    }

    /**
     * Queue Product Review scraping
     *
     * @param Account $account
     * @param Product $product
     */
    protected function queueProductReview($account, $product)
    {
        $attributes = [
            'account' => $account,
            'product' => $product,
        ];

        // Queue Product Review
        $job = (new ProductReviewJob($attributes))
            ->onQueue(config('bigdatty.amazon.sqs.products-reviews'));

        $this->dispatch($job);

        debug('ProductReview Queued');
    }

    /**
     * Queue Product Question scraping
     *
     * @param Account $account
     * @param Product $product
     */
    protected function queueProductQuestion($account, $product)
    {
        $attributes = [
            'account' => $account,
            'product' => $product,
        ];

        // Queue Product Review
        $job = (new ProductQuestionJob($attributes))
            ->onQueue(config('bigdatty.amazon.sqs.products-questions'));

        $this->dispatch($job);

        debug('ProductQuestion Queued');
    }

    /**
     * Queue Product Hijack scraping
     *
     * @param Account $account
     * @param Product $product
     */
    protected function queueProductHijack($account, $product)
    {
        //
    }

    /**
     * Queue Report
     *
     * @param Account $account
     * @param Report $report
     * @param array $options
     */
    protected function queueReport($account, $report, $options)
    {
        $attributes = [
            'account' => $account,
            'report'  => $report,
            'options' => json_encode($options),
        ];

        // Queue Product Review
        $job = (new ReportJob($attributes))
            ->onQueue(config('bigdatty.amazon.sqs.reports'))
            ->delay(config('bigdatty.reports.delay'));

        $this->dispatch($job);

        debug('Report Queued');
    }
}