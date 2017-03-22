<?php namespace App\Models\Product;

use App\Models\BaseRevisionable;
use App\Models\Account\Account;
use App\Models\Marketplace\Marketplace;
use App\Models\Order\OrderProduct;
use App\Traits\DetailsTrait;

class Product extends BaseRevisionable
{
    use DetailsTrait;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'account_id',
        'marketplace_id',
        'source_id',
        'sku',
        'name',
        'price',
        'quantity',
        'total_reviews',
        'total_questions',
    ];

    /*
     |--------------------------------------------------------------------------
     | RELATIONSHIPS
     |--------------------------------------------------------------------------
     */

    /**
     * Account one-to-many relationship
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Marketplace one-to-many relationship
     */
    public function marketplace()
    {
        return $this->belongsTo(Marketplace::class);
    }

    /**
     * ProductMetadata one-to-many relationship
     */
    public function metadata()
    {
        return $this->hasMany(ProductMetadata::class);
    }

    /**
     * OrderProduct one-to-many relationship
     */
    public function orders()
    {
        return $this->hasMany(OrderProduct::class);
    }

    /**
     * ProductQuestion one-to-many relationship
     */
    public function questions()
    {
        return $this->hasMany(ProductQuestion::class);
    }

    /**
     * ProductReview one-to-many relationship
     */
    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }
}