<?php namespace App\Models\Marketplace;

use App\Models\BaseModel;
use App\Models\Account\Account;
use App\Models\Credential\Credential;
use App\Models\Product\Product;
use App\Traits\DetailsTrait;

class Marketplace extends BaseModel
{
    use DetailsTrait;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'marketplaces';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'marketplace_type_id',
        'name',
        'domain',
        'credential_id',
    ];

    /*
     |--------------------------------------------------------------------------
     | RELATIONSHIPS
     |--------------------------------------------------------------------------
     */

    /**
     * Account one-to-many relationship
     */
    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    /**
     * Credential one-to-many relationship
     */
    public function credential()
    {
        return $this->belongsTo(Credential::class);
    }

    /**
     * MarketplaceMetadata one-to-many relationship
     */
    public function metadata()
    {
        return $this->hasMany(MarketplaceMetadata::class);
    }

    /**
     * Product one-to-many relationship
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * MarketplaceType one-to-many relationship
     */
    public function type()
    {
        return $this->belongsTo(MarketplaceType::class, 'marketplace_type_id');
    }
}