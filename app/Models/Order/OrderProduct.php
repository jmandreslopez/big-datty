<?php namespace App\Models\Order;

use App\Models\BaseModel;
use App\Models\Product\Product;
use App\Traits\DetailsTrait;

class OrderProduct extends BaseModel
{
    use DetailsTrait;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'orders_products';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'source_id',
        'quantity',
        'price',
    ];

    /*
     |--------------------------------------------------------------------------
     | RELATIONSHIPS
     |--------------------------------------------------------------------------
     */

    /**
     * OrderProductMetadata one-to-many relationship
     */
    public function metadata()
    {
        return $this->hasMany(OrderProductMetadata::class);
    }

    /**
     * Order one-to-many relationship
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Product one-to-many relationship
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}