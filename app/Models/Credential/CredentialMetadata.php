<?php namespace App\Models\Credential;

use App\Models\BaseModel;

class CredentialMetadata extends BaseModel
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'credentials_metadata';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'credential_id',
        'meta_key',
        'meta_value',
    ];

    /*
     |--------------------------------------------------------------------------
     | RELATIONSHIPS
     |--------------------------------------------------------------------------
     */

    /**
     * Credential one-to-many relationship
     */
    public function credential()
    {
        return $this->belongsTo(Credential::class);
    }
}