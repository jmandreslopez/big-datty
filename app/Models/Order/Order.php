<?php namespace App\Models\Order;

use App\Models\BaseRevisionable;
use App\Models\Account\Account;
use App\Models\Customer\Customer;
use App\Models\Feedback\Feedback;
use App\Traits\DetailsTrait;

class Order extends BaseRevisionable
{
    use DetailsTrait;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'account_id',
        'customer_id',
        'source_id',
        'status',
        'total',
        'channel',
        'type',
        'is_completed',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_completed' => 'boolean',
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
     * Customer one-to-many relationship
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Feedback one-to-many relationship
     */
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    /**
     * OrderMetadata one-to-many relationship
     */
    public function metadata()
    {
        return $this->hasMany(OrderMetadata::class);
    }

    /**
     * OrderProduct one-to-many relationship
     */
    public function products()
    {
        return $this->hasMany(OrderProduct::class);
    }
}