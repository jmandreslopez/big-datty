<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    /**
     * Prevent mass assignable columns
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        //
    ];

    /**
     * Indicates if the model should be timestamped
     *
     * @var bool
     */
    public $timestamps = false;
}