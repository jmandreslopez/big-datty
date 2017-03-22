<?php namespace App\Reports;

use App\Models\Account\Account;
use GuzzleHttp\Message\ResponseInterface;

abstract class BaseReport
{
    /**
     * @var Account
     */
    protected $account;

    /**
     * @var ResponseInterface
     */
    protected $report;

    /**
     * @var array
     */
    protected $options;

    /**
     * @param Account $account
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    /**
     * @param $response
     * @param array $options
     * @return mixed
     */
    abstract public function init($response, $options);
}