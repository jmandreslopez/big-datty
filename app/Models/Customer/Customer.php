<?php namespace App\Models\Customer;

use App\Models\BaseRevisionable;
use App\Models\Order\Order;

class Customer extends BaseRevisionable
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'customers';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
    ];

    /*
     |--------------------------------------------------------------------------
     | RELATIONSHIPS
     |--------------------------------------------------------------------------
     */

    /**
     * Order one-to-many relationship
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}