<?php namespace App\Models\Report;

use App\Models\BaseModel;
use App\Models\Account\Account;

class Report extends BaseModel
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'reports';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'account_id',
        'report_request_id',
        'type',
        'submitted_at',
        'start_date',
        'end_date',
        'status',
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
}