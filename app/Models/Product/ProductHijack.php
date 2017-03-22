<?php namespace App\Models\Product;

use App\Models\BaseRevisionable;
use App\Traits\DetailsTrait;

class ProductHijack extends BaseRevisionable
{
    use DetailsTrait;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'products_hijacks';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'date',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'date',
    ];

    /*
     |--------------------------------------------------------------------------
     | RELATIONSHIPS
     |--------------------------------------------------------------------------
     */

    /**
     * ProductHijackMetadata one-to-many relationship
     */
    public function metadata()
    {
        return $this->hasMany(ProductHijackMetadata::class, 'product_hijack_id');
    }

    /**
     * Product one-to-many relationship
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}