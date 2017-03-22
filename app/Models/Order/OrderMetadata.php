<?php namespace App\Models\Order;

use App\Models\BaseRevisionable;

class OrderMetadata extends BaseRevisionable
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'orders_metadata';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'meta_key',
        'meta_value',
    ];

    /*
     |--------------------------------------------------------------------------
     | RELATIONSHIPS
     |--------------------------------------------------------------------------
     */

    /**
     * Order one-to-many relationship
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}