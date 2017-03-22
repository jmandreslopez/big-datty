<?php namespace App\Models\Account;

use App\Models\BaseRevisionable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Marketplace\Marketplace;
use App\Models\Feedback\Feedback;
use App\Models\Order\Order;
use App\Models\Product\Product;
use App\Models\Report\Report;
use App\Traits\DetailsTrait;

class Account extends BaseRevisionable
{
    use SoftDeletes, DetailsTrait;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'accounts';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'member_id',
        'marketplace_id',
        'is_active',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /*
     |--------------------------------------------------------------------------
     | RELATIONSHIPS
     |--------------------------------------------------------------------------
     */

    /**
     * Feedback one-to-many relationship
     */
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    /**
     * AccountMetadata one-to-many relationship
     */
    public function metadata()
    {
        return $this->hasMany(AccountMetadata::class);
    }

    /**
     * Marketplace one-to-many relationship
     */
    public function marketplace()
    {
        return $this->belongsTo(Marketplace::class);
    }

    /**
     * Order one-to-many relationship
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Product one-to-many relationship
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * ReportRequest one-to-many relationship
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
