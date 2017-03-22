<?php namespace App\Console\Commands\Amazon\Product;

use App\Console\Commands\BaseScraperCommand;
use Symfony\Component\Console\Input\InputOption;
use App\Handlers\Amazon\Product\ProductReviewHandler;

class ProductReviewCommand extends BaseScraperCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'amazon:products:reviews';

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
     * @return ProductReviewHandler
     */
    protected function getHandler($arguments)
    {
        return new ProductReviewHandler($arguments);
    }
}