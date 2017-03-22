<?php namespace App\Console\Commands\Amazon\Order;

use App\Console\Commands\BaseRequestCommand;
use Symfony\Component\Console\Input\InputOption;
use App\Handlers\Amazon\Order\OrderHandler;
use GuzzleHttp\Client;

class OrderCommand extends BaseRequestCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'amazon:orders';

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
        $this->addOption('account', 'a', InputOption::VALUE_OPTIONAL, '');
        $this->addOption('date',    'd', InputOption::VALUE_OPTIONAL, '');
    }

    /**
     * Instantiate API section handler
     *
     * @param Client $client
     * @param array $arguments
     * @return OrderHandler
     */
    protected function getHandler($client, $arguments)
    {
        return new OrderHandler($client, $arguments);
    }
}