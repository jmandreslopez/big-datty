<?php namespace App\Models\Marketplace;

use App\Models\BaseModel;

class MarketplaceType extends BaseModel
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'marketplaces_types';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /*
     |--------------------------------------------------------------------------
     | RELATIONSHIPS
     |--------------------------------------------------------------------------
     */

    /**
     * Marketplace one-to-many relationship
     */
    public function marketplaces()
    {
        return $this->hasMany(Marketplace::class);
    }
}