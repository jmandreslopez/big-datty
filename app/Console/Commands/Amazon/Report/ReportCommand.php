<?php namespace App\Console\Commands\Amazon\Report;

use App\Console\Commands\BaseRequestCommand;
use Symfony\Component\Console\Input\InputOption;
use App\Handlers\Amazon\Report\ReportHandler;
use GuzzleHttp\Client;

class ReportCommand extends BaseRequestCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'amazon:reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Specify the arguments and options on the command.
     *
     * @return void
     */
    protected function specifyParameters()
    {
        $this->addOption('account',    'a', InputOption::VALUE_OPTIONAL, '');
        $this->addOption('type',       't', InputOption::VALUE_OPTIONAL, '');
        $this->addOption('start-date', 's', InputOption::VALUE_OPTIONAL, '');
    }

    /**
     * Instantiate API section handler
     *
     * @param Client $client
     * @param array $arguments
     * @return ReportHandler
     */
    protected function getHandler($client, $arguments)
    {
        return new ReportHandler($client, $arguments);
    }
}