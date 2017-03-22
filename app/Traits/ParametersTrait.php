<?php namespace App\Traits;

use App\Models\Account\Account;
use Carbon\Carbon;
use Exception;

trait ParametersTrait
{
    /**
     * Get API params for the request
     *
     * @param Account $account
     * @param string $action
     * @return mixed
     * @throws Exception
     */
    protected function getParameters($account, $action)
    {
        try {
            // Account Metadata
            $accountMetadata = $account->details();

            // Marketplace Metadata
            $marketplaceMetadata = $account->marketplace->details();

            // Marketplace Credentials
            $credentials = $account->marketplace->credential->details();

            return [
                'Action'           => $action,
                'Endpoint'         => $marketplaceMetadata['Endpoint'],
                'SellerId'         => $accountMetadata['SellerId'],
                'MWSAuthToken'     => $accountMetadata['MWSAuthToken'],
                'AWSAccessKeyId'   => $credentials['AWSAccessKeyId'],
                'SecretKey'        => $credentials['SecretKey'],
                'SignatureVersion' => 2,
                'SignatureMethod'  => 'HmacSHA256',
            ];
        }
        catch (Exception $e) {
            throw new Exception('Not found credentials for the Account (' . $account->id . ')');
        }
    }

    /**
     * @param $event
     * @param array $parameters
     * @param array $options
     */
    protected function addParameters($event, $parameters, $options)
    {
        $request = $event->getRequest();

        // Remove Endpoint
        $endpoint = $parameters['Endpoint'];
        unset($parameters['Endpoint']);

        // Remove SecretKey
        $secretKey = $parameters['SecretKey'];
        unset($parameters['SecretKey']);

        // Add Version
        $parameters['Version'] = $options['version'];

        // Add Timestamp
        $parameters['Timestamp'] = Carbon::now()->tz('utc')->toIso8601String();

        ksort($parameters, SORT_NATURAL);

        // Remove https
        $endpoint = str_replace('https://', '', $endpoint);

        // Write the signature
        $signature = 'POST' . "\n";
        $signature .= $endpoint . "\n";
        $signature .= $options['section'] . "\n";

        $first = true;
        foreach ($parameters as $key => $value) {
            $signature .= (!$first ? '&' : '') . rawurlencode($key) . '=' . rawurlencode($value);
            $first = false;
        }

        // Signature
        $signature = hash_hmac('sha256', $signature, $secretKey, true);
        $signature = base64_encode($signature);
        $parameters['Signature'] = $signature;

        // Get the query object to add the request parameters
        $query = $request->getQuery();

        // Copy the API params into the request object
        foreach ($parameters as $key => $value) {
            $query[$key] = $value;
        }
    }
}