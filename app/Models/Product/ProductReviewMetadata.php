<?php namespace App\Models\Product;

use App\Models\BaseRevisionable;

class ProductReviewMetadata extends BaseRevisionable
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'products_reviews_metadata';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'product_review_id',
        'meta_key',
        'meta_value',
    ];

    /*
     |--------------------------------------------------------------------------
     | RELATIONSHIPS
     |--------------------------------------------------------------------------
     */

    /**
     * ProductReview one-to-many relationship
     */
    public function review()
    {
        return $this->belongsTo(ProductReview::class);
    }
}