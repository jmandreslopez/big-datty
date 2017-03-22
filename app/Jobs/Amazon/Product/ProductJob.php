<?php namespace App\Jobs\Amazon\Product;

use App\Jobs\BaseJob;
use App\Models\Order\Order;
use App\Services\Product\ProductService;

class ProductJob extends BaseJob
{
    /**
     * @var Order
     */
    protected $order;

    /**
     * @var array
     */
    protected $item;

    /**
     * Initialize Job
     */
    protected function init()
    {
        $this->account = $this->attributes['account'];

        $this->order = $this->attributes['order'];

        $this->item = json_decode($this->attributes['item'], true);
    }

    /**
     * Process Job
     */
    protected function process()
    {
        ProductService::create($this->account, $this->order, $this->item);
    }
}