<?php namespace App\Models\Feedback;

use App\Models\BaseRevisionable;

class FeedbackMetadata extends BaseRevisionable
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'feedbacks_metadata';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'feedback_id',
        'meta_key',
        'meta_value',
    ];

    /*
     |--------------------------------------------------------------------------
     | RELATIONSHIPS
     |--------------------------------------------------------------------------
     */

    /**
     * Feedback one-to-many relationship
     */
    public function feedback()
    {
        return $this->belongsTo(Feedback::class);
    }
}