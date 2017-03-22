<?php namespace App\Models\Order;

use App\Models\BaseRevisionable;
use App\Models\Product\Product;

class OrderProductMetadata extends BaseRevisionable
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'orders_products_metadata';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'order_product_id',
        'meta_key',
        'meta_value',
    ];

    /*
     |--------------------------------------------------------------------------
     | RELATIONSHIPS
     |--------------------------------------------------------------------------
     */

    /**
     * OrderProduct one-to-many relationship
     */
    public function order_product()
    {
        return $this->belongsTo(OrderProduct::class);
    }
}