<?php namespace App\Handlers\Amazon\Seller;

use App\Handlers\BaseRequestHandler;
use App\Helpers\Amazon\SellerHelper;
use App\Models\Account\Account;
use App\Helpers\SnsClient;
use App\Services\Account\AccountService;
use GuzzleHttp\Event\CompleteEvent;
use GuzzleHttp\Event\ErrorEvent;

class SellerHandler extends BaseRequestHandler
{
    /**
     * Status
     *
     * @var null|string
     */
    protected $status;

    /**
     * Set Status
     *
     * @param null|string $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get Status
     *
     * @return null|string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Initialize Request
     */
    protected function init()
    {
        $this->options = SellerHelper::API_PARAMETERS;

        // Account Id
        if (! empty($this->arguments['account'])) {
            $this->setAccountId($this->arguments['account']);
        }

        // Status
        $this->setStatus($this->arguments['status']);
    }

    /**
     * Load the queue
     */
    protected function load()
    {
        $query = Account::select('*');

        // Account Id
        if (! empty($this->getAccountId())) {
            $query = Account::where('accounts.id', '=', $this->getAccountId());
        }

        // Add Status
        switch ($this->getStatus()) {
            case 'true':
                $query->whereIsActive(true);
                break;

            case 'false':
                $query->whereIsActive(false);
                break;

            case null:
                $query->whereNull('is_active');
                break;

            default:
                break;
        }

        debug('Account Count: ' . $query->count());

        $query->chunk(1000, function($accounts) {
            foreach ($accounts as $account) {
                $this->requestListMarketplaceParticipations($account, $this->options);
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

        if (! empty($response)) {

            $this->processVerification($account, 'pass');

            AccountService::enable($account);

            debug('Account Enabled: ' . $account->id);
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
        if ($event->getResponse()->getStatusCode() == '401') {

            $this->processVerification($account, 'failed');

            AccountService::disable($account);

            debug('Account Disabled: ' . $account->id);
        }
        else {
            debug('Request failed!', 'error');
            debug($event->getException());
        }
    }

    /**
     * Process SNS verification
     *
     * @param Account $account
     * @param string $result
     */
    protected function processVerification($account, $result)
    {
        if (! is_null($account->is_active)) {
            SnsClient::account('verification', $account->id, $result);
        }
    }
}