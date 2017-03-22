<?php namespace App\Jobs;

use App\Helpers\GuzzleClient;
use App\Traits\RequestTrait;
use GuzzleHttp\Event\CompleteEvent;
use GuzzleHttp\Event\ErrorEvent;
use Carbon\Carbon;

abstract class BaseRequestJob extends BaseJob
{
    use RequestTrait;

    /**
     * @var array
     */
    protected $options;

    /**
     * Initialize Job
     */
    protected function init()
    {
        $this->options = json_decode($this->attributes['options'], true);
    }

    /**
     * Synchronous POST Request
     *
     * @param array $parameters
     * @param array $options
     */
    protected function getRequest($parameters, $options)
    {
        $this->options = $options;

        $url = $parameters['Endpoint'] . $this->options['section'];

        // Remove Endpoint
        $endpoint = $parameters['Endpoint'];
        unset($parameters['Endpoint']);

        // Remove SecretKey
        $secretKey = $parameters['SecretKey'];
        unset($parameters['SecretKey']);

        $parameters['Version'] = $this->options['version'];
        $parameters['Timestamp'] = Carbon::now()->tz('utc')->toIso8601String();

        uksort($parameters, 'strcmp');

        // Remove https
        $endpoint = str_replace('https://', '', $endpoint);

        $queryParameters = [];
        foreach ($parameters as $key => $value) {
            $queryParameters[] = $key . '=' . rawurlencode($value);
        }

        // Signature to be signed
        $signature = "POST\n";
        $signature .= $endpoint."\n";
        $signature .= $this->options['section']."\n";
        $signature .= implode('&', $queryParameters);

        // Sign
        $parameters['Signature'] = base64_encode(hash_hmac('sha256', $signature, $secretKey, true));

        $client = $this->getClient();

        // Async POST Request
        $client->post($url, [
            'debug'  => config('bigdatty.guzzle.debug_http'),
            'headers' => config('bigdatty.guzzle.headers'),
            'query'  => $parameters,
            'events' => [
                'complete' => function ($event) {
                    $this->handleComplete($event);
                },
                'error' => function ($event) {
                    $this->handleError($event);
                }
            ],
        ]);
    }

    /**
     * Get Guzzle Client, meant to be overwritten by extended clases
     *
     * @return \GuzzleHttp\Client
     */
    protected function getClient()
    {
        return GuzzleClient::create();
    }

    /**
     * Request completed handler
     *
     * @param CompleteEvent $event
     */
    abstract protected function handleComplete($event);

    /**
     * Request error handler
     *
     * @param ErrorEvent $event
     */
    protected function handleError($event)
    {
        debug('Request failed!', 'error');
        debug($event->getException());
    }

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