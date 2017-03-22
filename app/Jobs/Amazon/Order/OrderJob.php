<?php namespace App\Jobs\Amazon\Order;

use App\Jobs\BaseJob;
use App\Helpers\Amazon\OrderHelper;
use App\Services\Order\OrderService;
use App\Traits\QueueTrait;

class OrderJob extends BaseJob
{
    use QueueTrait;

    /**
     * @var array
     */
    protected $item;

    /**
     * @var array
     */
    protected $options;

    /**
     * Initialize Job
     */
    public function init()
    {
        $this->account = $this->attributes['account'];

        $this->item = json_decode($this->attributes['item'], true);

        $this->options = json_decode($this->attributes['options'], true);
    }

    /**
     * Process Job
     */
    protected function process()
    {
        $order = OrderService::create($this->account, $this->item);

        if (! empty($order) && $order->status == OrderHelper::STATUS_PENDING) {
            $this->queueOrderPending($this->account, $order, $this->options);
        }
    }
}