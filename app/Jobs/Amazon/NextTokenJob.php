<?php namespace App\Jobs\Amazon;

use App\Jobs\BaseRequestJob;
use App\Models\Account\Account;
use App\Models\Order\Order;
use GuzzleHttp\Event\CompleteEvent;

class NextTokenJob extends BaseRequestJob
{
    /**
     * @var string
     */
    protected $action;

    /**
     * Process Job
     */
    protected function process()
    {
        $this->action = $this->attributes['action'];

        $token = $this->attributes['token'];

        $this->requestNextToken($this->account, $this->action, $token, $this->options, true);
    }

    /**
     * Request completed handler
     *
     * @param CompleteEvent $event
     */
    protected function handleComplete($event)
    {
        $response = $this->parse($event);

        switch ($this->action) {
            case 'ListOrdersByNextToken':
                $this->processOrders($response['ListOrdersByNextTokenResult']);
                break;

            case 'ListOrderItemsByNextToken':
                $this->processProducts($response['ListOrderItemsByNextTokenResult']);
                break;

            case 'GetReportRequestListByNextToken':
                $this->processReports($response);
                break;

            default:
                break;
        }
    }

    /**
     * Process NextToken
     *
     * @param array $response
     */
    protected function processNextToken($response)
    {
        if (array_key_exists('NextToken', $response)) {
            $this->queueNextToken($this->account, $this->action, $response['NextToken'], $this->options);
        }
    }


    /**
     * Process Orders
     *
     * @param array $response
     */
    protected function processOrders($response)
    {
        $this->processNextToken($response);

        debug('Processing Orders for Account: ' . $this->account->id);

        if (! empty($response['Orders'])) {
            if (isset($response['Orders']['Order'][0])) {
                foreach ($response['Orders']['Order'] as $item) {
                    $this->queueOrder($this->account, $item, $this->options); // Queue Order
                }
            }
            else {
                $this->queueOrder($this->account, $response['Orders']['Order'], $this->options); // Queue Order
            }
        }
    }

    /**
     * Process Products
     *
     * @param array $response
     */
    protected function processProducts($response)
    {
        $this->processNextToken($response);

        debug('Processing Product for Account Id: ' . $this->account->id);

        if (! empty($response['OrderItems'])) {
            if (isset($response['OrderItems']['OrderItem'][0])) {
                foreach ($response['OrderItems']['OrderItem'] as $item) {
                    $order = $this->options['order'];
                    $this->queueProduct($this->account, $order, $item); // Queue Product
                }
            }
            else {
                $order = $this->options['order'];
                $this->queueProduct($this->account, $order, $response['OrderItems']['OrderItem']); // Queue Product
            }
        }
    }

    /**
     * Process Reports
     *
     * @param array $response
     */
    protected function processReports($response)
    {
        //
    }
}