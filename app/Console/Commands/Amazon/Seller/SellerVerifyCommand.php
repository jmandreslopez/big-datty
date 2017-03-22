<?php namespace App\Console\Commands\Amazon\Seller;

use App\Console\Commands\BaseRequestCommand;
use Symfony\Component\Console\Input\InputOption;
use App\Handlers\Amazon\Seller\SellerHandler;
use GuzzleHttp\Client;

class SellerVerifyCommand extends BaseRequestCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'amazon:sellers';

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
        $this->addOption('status',  's', InputOption::VALUE_OPTIONAL, '');
    }

    /**
     * Instantiate API section handler
     *
     * @param Client $client
     * @param array $arguments
     * @return SellerHandler
     */
    protected function getHandler($client, $arguments)
    {
        return new SellerHandler($client, $arguments);
    }
}