<?php namespace App\Models\Product;

use App\Models\BaseRevisionable;

class ProductMetadata extends BaseRevisionable
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'products_metadata';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'meta_key',
        'meta_value',
    ];

    /*
     |--------------------------------------------------------------------------
     | RELATIONSHIPS
     |--------------------------------------------------------------------------
     */

    /**
     * Product one-to-many relationship
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}