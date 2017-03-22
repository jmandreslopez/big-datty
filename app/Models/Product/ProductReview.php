<?php namespace App\Models\Product;

use App\Models\BaseRevisionable;
use App\Traits\DetailsTrait;

class ProductReview extends BaseRevisionable
{
    use DetailsTrait;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'products_reviews';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'source_id',
        'stars',
        'date',
        'url',
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
     * ProductReviewMetadata one-to-many relationship
     */
    public function metadata()
    {
        return $this->hasMany(ProductReviewMetadata::class, 'product_review_id');
    }

    /**
     * Product one-to-many relationship
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}