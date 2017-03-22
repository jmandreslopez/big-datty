<?php namespace App\Models\Marketplace;

use App\Models\BaseModel;

class MarketplaceMetadata extends BaseModel
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'marketplaces_metadata';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'marketplace_id',
        'meta_key',
        'meta_value',
    ];

    /*
     |--------------------------------------------------------------------------
     | RELATIONSHIPS
     |--------------------------------------------------------------------------
     */

    /**
     * Marketplace one-to-many relationship
     */
    public function marketplace()
    {
        return $this->belongsTo(Marketplace::class);
    }
}