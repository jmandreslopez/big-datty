<?php namespace App\Handlers;

use App\Traits\QueueTrait;

abstract class BaseScraperHandler extends BaseHandler
{
    use QueueTrait;

    /**
     * @param array $arguments
     */
    public function __construct($arguments = [])
    {
        parent::__construct($arguments);
    }
}