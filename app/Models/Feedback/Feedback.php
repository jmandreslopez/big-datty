<?php namespace App\Models\Feedback;

use App\Models\BaseRevisionable;
use App\Models\Account\Account;
use App\Models\Order\Order;
use App\Traits\DetailsTrait;

class Feedback extends BaseRevisionable
{
    use DetailsTrait;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'feedbacks';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'account_id',
        'order_id',
        'date',
        'rating',
        'is_active',
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
     * Account one-to-many relationship
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * FeedbackMetadata one-to-many relationship
     */
    public function metadata()
    {
        return $this->hasMany(FeedbackMetadata::class);
    }

    /**
     * Order one-to-many relationship
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}