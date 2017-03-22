<?php namespace App\Jobs\Amazon\Order;

use App\Jobs\BaseReportJob;
use App\Services\Order\OrderService;

class OrderReportJob extends BaseReportJob
{
    /**
     * Process Job
     */
    protected function process()
    {
        //$order = $this->createOrderFromSalesReport($account, $properties);

        //$product = $this->createProductFromSalesReport($account, $order, $properties);

        //$this->convertKeysToCamelCase($this->item);

        OrderService::create($this->account, $this->item);
    }
}