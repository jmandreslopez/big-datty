<?php namespace App\Console\Commands;

use App\Helpers\GuzzleClient;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;

abstract class BaseRequestCommand extends BaseCommand
{
    /**
     * Process Command
     *
     * @param array $arguments
     * @return bool
     */
    protected function process($arguments)
    {
        $client = GuzzleClient::create();

        // Each command instance defines its handler
        $handler = $this->getHandler($client, $arguments);

        if ($handler->countRequests() == 0) {
            return false;
        }

        $requests = $handler->getRequests();

        // Pool options
        $options = [
            'pool_size' => config('bigdatty.pool_size'),
            'before' => function ($event) {
                debug_pool('before', $event);
            },
            'complete' => function ($event) {
                debug_pool('complete', $event);
            },
            'error' => function ($event) {
                debug_pool('error', $event);
            }
        ];

        $pool = new Pool($client, $requests, $options);

        // Start the queue pool
        $pool->wait();
    }

    /**
     * Instantiate API section handler
     *
     * @param Client $client
     * @param array $arguments
     * @return mixed
     */
    abstract protected function getHandler($client, $arguments);
}