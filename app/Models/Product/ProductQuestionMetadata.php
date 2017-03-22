<?php namespace App\Models\Product;

use App\Models\BaseRevisionable;

class ProductQuestionMetadata extends BaseRevisionable
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'products_questions_metadata';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'product_question_id',
        'meta_key',
        'meta_value',
    ];

    /*
     |--------------------------------------------------------------------------
     | RELATIONSHIPS
     |--------------------------------------------------------------------------
     */

    /**
     * ProductQuestion one-to-many relationship
     */
    public function question()
    {
        return $this->belongsTo(ProductQuestion::class);
    }
}