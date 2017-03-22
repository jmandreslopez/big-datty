<?php namespace App\Handlers;

use App\Models\Account\Account;
use App\Traits\RequestTrait;
use GuzzleHttp\Client;
use GuzzleHttp\Event\CompleteEvent;
use GuzzleHttp\Event\ErrorEvent;

abstract class BaseRequestHandler extends BaseHandler
{
    use RequestTrait;

    /**
     * Guzzle Client
     *
     * @var Client
     */
    protected $client;

    /**
     * Guzzle Requests
     *
     * @var array
     */
    protected $requests = [];

    /**
     * Options
     *
     * @var array
     */
    protected $options;

    /**
     * Account Id
     *
     * @var int
     */
    protected $accountId;

    /**
     * Returns an Iterator for the request queue to be used by the pooler
     *
     * @return array
     */
    public function getRequests()
    {
        return $this->requests;
    }

    /**
     * Find out how many queued requests there are
     *
     * @return int
     */
    public function countRequests()
    {
        return $this->requests->count();
    }

    /**
     * Set Account Id
     *
     * @param $accountId
     * @return $this
     */
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * Get Account Id
     *
     * @return int
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * @param Client $client
     * @param array $arguments
     */
    public function __construct($client, $arguments = [])
    {
        $this->client = $client;

        $this->requests = new \ArrayIterator([]);

        $this->options = [];

        parent::__construct($arguments);
    }

    /**
     * Create a new request to send into the pooler
     *
     * @param Account $account
     * @param $parameters
     * @param $options
     */
    public function addRequest($account, $parameters, $options)
    {
        $url = $parameters['Endpoint'] . $options['section'];

        // Set up a new API request
        $request = $this->client->createRequest('POST', $url, [
            'debug' => config('bigdatty.guzzle.debug_http'),
            'headers' => config('bigdatty.guzzle.headers'),
            'events' => [
                'before' => function($event) use ($parameters, $options) {
                    $this->addParameters($event, $parameters, $options);
                },
                'complete' => function ($event) use ($account, $options) {
                    $this->handleComplete($event, $account, $options);
                },
                'error' => function ($event) use ($account, $options) {
                    $this->handleError($event, $account, $options);
                }
            ],
        ]);

        // Append the request to the request queue being processed by the pooler
        $this->requests->append($request);
    }

    /**
     * Request completed handler
     *
     * @param CompleteEvent $event
     * @param Account $account
     * @param array $options
     */
    abstract protected function handleComplete($event, $account, $options);

    /**
     * Request error handler
     *
     * @param ErrorEvent $event
     * @param Account $account
     * @param array $options
     */
    abstract protected function handleError($event, $account, $options);

    /**
     * Parse Response
     *
     * @param CompleteEvent $event
     * @return array
     */
    protected function parse($event)
    {
        $content = $event->getResponse()->getBody()->getContents();

        return parse_xml($content);
    }
}