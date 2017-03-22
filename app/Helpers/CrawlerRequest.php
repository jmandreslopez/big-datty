<?php namespace App\Helpers;

use TorControl\TorControl;
use Exception;

class CrawlerRequest
{
    /**
     * Get a new Tor identity using Tor Control
     */
    public static function newTorIdentity()
    {
        debug('Renewing Tor identity', 'warning');

        try {
            $torControl = new TorControl(config('bigdatty.crawler.control'));
            $torControl->connect();
            $torControl->authenticate();
            $torControl->executeCommand('SIGNAL NEWNYM');
            $torControl->quit();

            sleep(10);  // wait 10 seconds
        }
        catch (Exception $e) {
            debug('Tor Control - ' . $e->getMessage());
        }
    }

    /**
     * Get Request using Tor
     *
     * @param string $url
     * @param array $options
     * @return array
     */
    public static function getRequest($url, $options = [])
    {
        try {
            $client = GuzzleClient::create();

            // Request options
            if (empty($options)) {
                $options = array_merge(config('bigdatty.crawler.request'), [
                    'headers' => [
                        'Accept'       => 'image/gif,image/x-bitmap,image/jpeg,image/pjpeg',
                        'Connection'   => 'keep-alive',
                        'Content-type' => 'application/x-www-form-urlencoded;charset=UTF-8',
                        'User-Agent'   => UserAgents::getRandom(),
                    ]
                ]);
            }

            debug('CrawlerRequest: ' . $url);

            $response = $client->get($url, $options);
            if (! empty($response)) {
                $response = [
                    'statusCode'        => $response->getStatusCode(),
                    'bodyContent'       => $response->getBody()->getContents(),
                    'headerContentType' => $response->getHeaders()['Content-Type'][0],
                ];

                // Captcha check
                if ($response['statusCode'] == 200) {   // Only 200 OK
                    if (stripos($response['bodyContent'], 'Robot Check') !== false) {
                        debug('Captcha!', 'error');
                        self::newTorIdentity();

                        return self::getRequest($url, $options);
                    }

                    return $response;
                }
            }
        }
        catch (Exception $e) {
            debug('Crawler Request - ' . $e->getMessage());
        }

        return false;
    }
}