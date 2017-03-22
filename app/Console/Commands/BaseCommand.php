<?php namespace App\Console\Commands;

use Illuminate\Console\Command;

abstract class BaseCommand extends Command
{
    /**
     * Fire Command
     *
     * @return bool
     */
    public function fire()
    {
        // Set memory_limit to 1024M
        ini_set('memory_limit', config('bigdatty.general.memory_limit'));

        $arguments = array_merge($this->argument(), $this->option());
        $command = $arguments['command'];
        unset($arguments['command']);

        debug('Executing ' . $command, 'good');

        $this->process($arguments);

        debug('Finished ' . $command, 'good');

        return false;
    }

    /**
     * Process Command
     *
     * @param array $arguments
     * @return bool
     */
    abstract protected function process($arguments);
}