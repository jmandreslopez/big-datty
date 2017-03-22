<?php namespace App\Helpers;

use GuzzleHttp\Event\AbstractTransferEvent;
use GuzzleHttp\Subscriber\Retry\RetrySubscriber;
use GuzzleHttp\Client;

class GuzzleClient
{
	/**
	 * Instantiates a client with a retry/exponentialBackoff plugin
	 * Can create different clients for different marketplaces if need be
	 *
	 * @param array $options
	 * @return Client
	 */
	public static function create($options = [])
	{
		static $client;

		if (isset($client)) {
			return $client;
		}

		$default = [
			'filter' => self::retryFilter(),
			'max'    => config('bigdatty.guzzle.retry.max'),
			'delay'  => self::retryDelay(),
		];

		$options = array_merge($default, $options);

		// Create RetrySubscriber Object
		$retry = new RetrySubscriber($options);

		// Create HTTP Client
		$client = new Client();

		// Attach the retry plugin
		$client->getEmitter()->attach($retry);

		return $client;
	}

	/**
	 * @return \Closure
	 */
	protected static function retryFilter()
	{
		return function ($retries, AbstractTransferEvent $event) {
			$code = $event->getResponse() ? $event->getResponse()->getStatusCode() : null;
			if (in_array($code, config('bigdatty.guzzle.retry.status_codes'))) {
				debug('[Retry] StatusCode: ' . $code, 'warning');

				return true;
			}

			return false;
		};
	}

	/**
	 * @return \Closure
	 */
	protected static function retryDelay()
	{
		return function ($retries) {
			$delay = (int) pow(2, $retries - 1) * 10000;
			debug('[Retry] Number: '.$retries.' with '.$delay);

			return $delay;
		};
	}
}