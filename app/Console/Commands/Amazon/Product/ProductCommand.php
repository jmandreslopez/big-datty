<?php namespace App\Console\Commands\Amazon\Product;

use App\Console\Commands\BaseRequestCommand;
use Symfony\Component\Console\Input\InputOption;
use App\Handlers\Amazon\Product\ProductHandler;
use GuzzleHttp\Client;

class ProductCommand extends BaseRequestCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'amazon:products';

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
        $this->addOption('order',   'o', InputOption::VALUE_OPTIONAL, '');
    }

    /**
     * Instantiate API section handler
     *
     * @param Client $client
     * @param array $arguments
     * @return ProductHandler
     */
    protected function getHandler($client, $arguments)
    {
        return new ProductHandler($client, $arguments);
    }
}