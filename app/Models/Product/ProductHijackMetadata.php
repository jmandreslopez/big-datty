<?php namespace App\Models\Product;

use App\Models\BaseRevisionable;

class ProductHijackMetadata extends BaseRevisionable
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'products_hijacks_metadata';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'product_hijack_id',
        'meta_key',
        'meta_value',
    ];

    /*
     |--------------------------------------------------------------------------
     | RELATIONSHIPS
     |--------------------------------------------------------------------------
     */

    /**
     * ProductHijack one-to-many relationship
     */
    public function question()
    {
        return $this->belongsTo(ProductHijack::class);
    }
}