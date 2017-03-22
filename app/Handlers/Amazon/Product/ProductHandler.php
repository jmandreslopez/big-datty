<?php namespace App\Handlers\Amazon\Product;

use App\Handlers\BaseRequestHandler;
use App\Helpers\Amazon\ProductHelper;
use App\Models\Account\Account;
use App\Models\Order\Order;
use App\Services\Order\OrderService;
use GuzzleHttp\Event\CompleteEvent;
use GuzzleHttp\Event\ErrorEvent;

class ProductHandler extends BaseRequestHandler
{
    /**
     * Order Id
     *
     * @var int
     */
    protected $orderId;

    /**
     * Orders Queue
     *
     * @var array
     */
    protected $orders;

    /**
     * Account List
     *
     * @var array
     */
    protected $accountsList = [];

    /**
     * Set Order Id
     *
     * @param $orderId
     * @return $this
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * Get Order Id
     *
     * @return int
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Get Order Queue count
     *
     * @return int
     */
    protected function countQueue()
    {
        return (!empty($this->orders)) ? $this->orders->count() : 0;
    }

    /**
     * Initialize Request
     */
    protected function init()
    {
        $this->options = ProductHelper::API_PARAMETERS;

        // Account Id
        if (! empty($this->arguments['account'])) {
            $this->setAccountId($this->arguments['account']);
        }

        // Order Id
        if (! empty($this->arguments['order'])) {
            $this->setOrderId($this->arguments['order']);
        }
    }

    /**
     * Create a series of requests to get items for not completed orders
     *
     * Because we are limited by API access restrictions, we can only process them for one account at a time, linearly
     * So only allow 1 request per account in the queue and replenish the queue as we go
     * But we can process multiple accounts asynchronously
     */
    protected function load()
    {
        if ($this->countQueue() < 100) {

            $query = Order::whereIsCompleted(0);

            // Order Id
            if (! empty($this->getOrderId())) {
                $query->where('id', '=', $this->getOrderId());
            }

            if (count($this->accountsList) > 0) {
                $query->whereNotIn('account_id', $this->accountsList);
            }

            // Get unfinished Orders
            $this->orders = $query->with('account')->get();
        }

        if ($this->countQueue() > 0) {

            foreach($this->orders as $key => $order) {

                $account = $order->account;

                if (!isset($this->accountsList[$account->id])) {

                    $this->requestListOrderItems($account, $order, $this->options);

                    // Take the order out of the queue
                    $this->orders->forget($key);

                    // Set up a mutual exclusion, only one account can be in the
                    // pooler at a time to maximize throughput
                    $this->accountsList[$account->id] = $account->id;
                }
            }
        }
    }

    /**
     * Request completed handler
     *
     * @param CompleteEvent $event
     * @param Account $account
     * @param array $options
     */
    protected function handleComplete($event, $account, $options)
    {
        $order = $options['order'];

        // Parse Response
        $response = $this->parse($event);

        switch ($options['action']) {
            case 'ListOrderItems':
                $response = $response['ListOrderItemsResult'];
                break;

            case 'ListOrderItemsByNextToken':
                $response = $response['ListOrderItemsByNextTokenResult'];
                break;

            default:
                break;
        }

        $this->process($response, $account, $order, $options);

        OrderService::completed($order);

        // Remove the account id from the list to let the queue loader
        // add another request for it, if there are some left
        unset($this->accountsList[$account->id]);

        // Add more requests
        $this->load();
    }

    /**
     * Request error handler
     *
     * @param ErrorEvent $event
     * @param Account    $account
     * @param array      $options
     */
    protected function handleError($event, $account, $options)
    {
        debug('Request failed!', 'error');
        debug($event->getException());
    }

    /**
     * Process OrderItems
     *
     * @param array   $response
     * @param Account $account
     * @param Order   $order
     * @param array   $options
     */
    protected function process($response, $account, $order, $options)
    {
        // Queue NextToken
        if (array_key_exists('NextToken', $response)) {
            $this->queueNextToken($account, 'ListOrderItemsByNextToken', $response['NextToken'], $options);
        }

        debug('Processing Product for Account Id: ' . $account->id);

        if (! empty($response['OrderItems'])) {
            if (isset($response['OrderItems']['OrderItem'][0])) {
                foreach ($response['OrderItems']['OrderItem'] as $item) {
                    $this->queueProduct($account, $order, $item); // Queue Product
                }
            }
            else {
                $this->queueProduct($account, $order, $response['OrderItems']['OrderItem']); // Queue Product
            }
        }
    }
}