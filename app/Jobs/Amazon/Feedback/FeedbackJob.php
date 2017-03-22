<?php namespace App\Jobs\Amazon\Feedback;

use App\Jobs\BaseRequestJob;
use App\Helpers\Amazon\OrderHelper;
use App\Helpers\SnsClient;
use App\Services\Feedback\FeedbackService;
use App\Services\Order\OrderService;
use GuzzleHttp\Event\CompleteEvent;

class FeedbackJob extends BaseRequestJob
{
    /**
     * @var array
     */
    protected $items;

    /**
     * Process Job
     */
    protected function process()
    {
        $this->items = json_decode($this->attributes['items'], true);

        // Set API options for Orders
        $this->options = array_merge($this->options, OrderHelper::API_PARAMETERS);

        $this->requestGetOrder($this->account, array_keys($this->items), $this->options);
    }

    /**
     * Request completed handler
     *
     * @param CompleteEvent $event
     */
    protected function handleComplete($event)
    {
        $response = $this->parse($event);

        $order = OrderService::create($this->account, $response['GetOrderResult']['Orders']['Order']);

        if (! empty($order)) {
            foreach ($this->items[$order->source_id] as $id) {
                $feedback = FeedbackService::update($id, $order);
                SnsClient::feedback('new', $feedback->id);
            }
        }
    }
}