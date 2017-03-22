<?php namespace App\Models;

use Venturecraft\Revisionable\RevisionableTrait;

abstract class BaseRevisionable extends BaseModel
{
    use RevisionableTrait;

    /**
     * Enable revisions
     */
    protected $revisionEnabled = true;

    /**
     * Remove old revisions
     */
    protected $revisionCleanup = true;

    /**
     * Maintain a maximum of 10 changes at any point
     * of time, while cleaning up old revisions
     */
    protected $historyLimit = 10;
}