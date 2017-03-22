<?php namespace App\Services\Account;

use App\Models\Account\Account;
use App\Models\Account\AccountMetadata;

class AccountService
{
    /**
     * Create Account
     *
     * @param array $item
     * @return Account|bool
     */
    public static function create($item)
    {
        $count = Account::whereMemberId($item['member_id'])
            ->whereMarketplaceId($item['marketplace_id'])
            ->count();

        if ($count == 0) {
            $account = new Account([
                'member_id'      => $item['member_id'],
                'marketplace_id' => $item['marketplace_id'],
            ]);

            // Save Account
            $account->save();

            // Create Metadata
            self::metadata($account, $item);

            debug('Account Created: ' . $account->id);

            return $account;
        }

        return false;
    }

    /**
     * Create Metadata
     *
     * @param Account $account
     * @param array $item
     */
    protected static function metadata($account, $item)
    {
        AccountMetadata::insert([
            [
                'account_id' => $account->id,
                'meta_key'   => 'SellerId',
                'meta_value' => $item['seller_id'],
            ],
            [
                'account_id' => $account->id,
                'meta_key'   => 'MWSAuthToken',
                'meta_value' => $item['auth_token'],
            ]
        ]);
    }

    /**
     * Enable Account
     *
     * @param $account
     */
    public static function enable($account)
    {
        $account->is_active = 1;
        $account->save();
    }

    /**
     * Disable Account
     *
     * @param $account
     */
    public static function disable($account)
    {
        $account->is_active = 0;
        $account->save();
    }

    /**
     * Get an specific value for a metadata key for the current account
     *
     * @param Account $account
     * @param string $key
     * @param null $type
     * @return string
     */
    public static function value($account, $key, $type = null)
    {
        $query = $account->metadata()->whereMetaKey($key);

        if (! empty($type)) {
            $query->where('marketplace_type_id', '=', $type);
        }

        $metadata = $query->first();

        return meta_value($metadata->meta_value);
    }

    /**
     * Get Country of an Account
     *
     * @param Account $account
     * @return mixed
     */
    public static function country($account)
    {
        return $account->marketplace->country_code;
    }
}