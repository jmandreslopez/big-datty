<?php namespace App\Models\Account;

use App\Models\BaseRevisionable;

class AccountMetadata extends BaseRevisionable
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'accounts_metadata';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'account_id',
        'meta_key',
        'meta_value',
    ];

    /*
     |--------------------------------------------------------------------------
     | RELATIONSHIPS
     |--------------------------------------------------------------------------
     */

    /**
     * Account one-to-many relationship
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}