<?php namespace App\Models\Product;

use App\Models\BaseRevisionable;
use App\Traits\DetailsTrait;

class ProductQuestion extends BaseRevisionable
{
    use DetailsTrait;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'products_questions';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'source_id',
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
     * ProductQuestionMetadata one-to-many relationship
     */
    public function metadata()
    {
        return $this->hasMany(ProductQuestionMetadata::class, 'product_question_id');
    }

    /**
     * Product one-to-many relationship
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}