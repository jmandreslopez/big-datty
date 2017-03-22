<?php namespace App\Handlers\Amazon\Order;

use App\Handlers\BaseRequestHandler;
use App\Helpers\Amazon\OrderHelper;
use App\Models\Account\Account;
use GuzzleHttp\Event\CompleteEvent;
use GuzzleHttp\Event\ErrorEvent;
use Carbon\Carbon;
use Exception;

class OrderHandler extends BaseRequestHandler
{
    /**
     * Last Update
     *
     * @var string
     */
    protected $lastUpdate;

    /**
     * Set Last Update
     *
     * @param $lastUpdate
     * @return $this
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    /**
     * Get Last Update
     *
     * @return string
     */
    public function getLastUpdate()
    {
        return Carbon::now()->subHour()->toDateTimeString();
    }

    /**
     * Initialize
     */
    protected function init()
    {
        $this->options = OrderHelper::API_PARAMETERS;

        // Account Id
        if (! empty($this->arguments['account'])) {
            $this->setAccountId($this->arguments['account']);
        }

        // Last Update
        if (! empty($this->arguments['date'])) {
            $this->setLastUpdate($this->arguments['date']);
        }
    }

    /**
     * Load Requests into the queue
     */
    protected function load()
    {
        $query = Account::whereIsActive(true);

        // Account Id
        if (!empty($this->getAccountId())) {
            $query->where('accounts.id', '=', $this->getAccountId());
        }

        debug('Account Count: ' . $query->count());

        $query->chunk(1000, function ($accounts) {
            foreach ($accounts as $account) {
                $this->requestListOrders($account, $this->options);
            }
        });
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
        // Parse Response
        $response = $this->parse($event);

        switch ($options['action']) {
            case 'ListOrders':
                $response = $response['ListOrdersResult'];
                break;

            case 'ListOrdersByNextToken':
                $response = $response['ListOrdersByNextTokenResult'];
                break;

            default:
                $response = null;
                break;
        }

        if (! empty($response)) {
            $this->process($response, $account, $options);
        }
    }

    /**
     * Request error handler
     *
     * @param ErrorEvent $event
     * @param Account $account
     * @param array $options
     */
    protected function handleError($event, $account, $options)
    {
        debug('Request failed!', 'error');
        debug($event->getException());
    }

    /**
     * Process Order
     *
     * @param $response
     * @param Account $account
     * @param array $options
     * @throws Exception
     */
    protected function process($response, $account, $options)
    {
        if (array_key_exists('NextToken', $response)) {
            $this->queueNextToken($account, 'ListOrdersByNextToken', $response['NextToken'], $options);
        }

        debug('Processing Orders for Account: ' . $account->id);

        if (! empty($response['Orders'])) {
            if (isset($response['Orders']['Order'][0])) {
                foreach ($response['Orders']['Order'] as $item) {
                    $this->queueOrder($account, $item, $options); // Queue Order
                }
            }
            else {
                $this->queueOrder($account, $response['Orders']['Order'], $options); // Queue Order
            }
        }
    }
}