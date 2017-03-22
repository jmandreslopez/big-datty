<?php namespace App\Models\Credential;

use App\Models\BaseModel;
use App\Models\Marketplace\Marketplace;
use App\Traits\DetailsTrait;

class Credential extends BaseModel
{
    use DetailsTrait;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'credentials';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'email',
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

    /**
     * CredentialMetadata one-to-many relationship
     */
    public function metadata()
    {
        return $this->hasMany(CredentialMetadata::class);
    }
}