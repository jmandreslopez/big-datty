<?php namespace App\Jobs;

abstract class BaseReportJob extends BaseJob
{
    /**
     * @var array
     */
    protected $item;

    /**
     * Initialize Job
     */
    protected function init()
    {
        $this->account = $this->attributes['account'];

        $this->item = json_decode($this->attributes['item'], true);
    }
}