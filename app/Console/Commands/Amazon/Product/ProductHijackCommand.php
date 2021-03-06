<?php namespace App\Console\Commands\Amazon\Product;

use App\Console\Commands\BaseScraperCommand;
use Symfony\Component\Console\Input\InputOption;
use App\Handlers\Amazon\Product\ProductHijackHandler;

class ProductHijackCommand extends BaseScraperCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'amazon:products:hijacks';

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
        $this->addOption('product', 'p', InputOption::VALUE_OPTIONAL, '');
        $this->addOption('asin',   null, InputOption::VALUE_OPTIONAL, '');
    }

    /**
     * @param array $arguments
     * @return ProductHijackHandler
     */
    protected function getHandler($arguments)
    {
        return new ProductHijackHandler($arguments);
    }
}