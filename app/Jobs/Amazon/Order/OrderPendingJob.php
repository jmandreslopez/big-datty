<?php namespace App\Jobs\Amazon\Order;

use App\Jobs\BaseRequestJob;
use App\Helpers\Amazon\OrderHelper;
use App\Helpers\GuzzleClient;
use App\Models\Order\Order;
use App\Services\Order\OrderService;
use GuzzleHttp\Event\AbstractTransferEvent;
use GuzzleHttp\Event\CompleteEvent;
use GuzzleHttp\Event\ErrorEvent;
use GuzzleHttp\Exception\ServerException;

class OrderPendingJob extends BaseRequestJob
{
    /**
     * @var Order
     */
    protected $order;

    /**
     * Get Guzzle Client
     *
     * @return \GuzzleHttp\Client
     */
    protected function getClient()
    {
        $options = [
            'filter' => function ($retries, AbstractTransferEvent $event) {
                $codes = [
                    400, // Bad Request
                    500, // Internal Server Error
                ];

                $code = $event->getResponse() ? $event->getResponse()->getStatusCode() : null;
                if (in_array($code, $codes)) {
                    debug('[Retry] StatusCode: ' . $code, 'warning');

                    return true;
                }

                return false;
            }
        ];

        return GuzzleClient::create($options);
    }

    /**
     * Process Job
     */
    protected function process()
    {
        try {
            $this->order = $this->attributes['order'];

            $this->requestGetOrder($this->account, $this->order, $this->options);
        }
        catch (ServerException $e) {
            $this->queueOrderPending($this->account, $this->order, $this->options);   // Requeue
        }
    }

    /**
     * Request completed handler
     *
     * @param CompleteEvent $event
     */
    protected function handleComplete($event)
    {
        $response = $this->parse($event);

        foreach ($response['GetOrderResult']['Orders'] as $item) {
            if ($item['OrderStatus'] != OrderHelper::STATUS_PENDING) {
                OrderService::update($this->order, $item);
            }
            else {
                $this->queueOrderPending($this->account, $this->order, $this->options);   // Requeue
            }
        }
    }

    /**
     * Request error handler
     *
     * @param ErrorEvent $event
     */
    protected function handleError($event)
    {
        if ($event->getResponse()->getStatusCode() != '503') {
            debug('Request failed!', 'error');
            debug($event->getException());
        }
    }
}