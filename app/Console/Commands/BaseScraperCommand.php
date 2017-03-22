<?php namespace App\Console\Commands;

abstract class BaseScraperCommand extends BaseCommand
{
    /**
     * Process Command
     *
     * @param array $arguments
     * @return bool
     */
    protected function process($arguments)
    {
        $this->getHandler($arguments);
    }

    /**
     * @param $arguments
     */
    abstract protected function getHandler($arguments);
}