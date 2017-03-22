<?php namespace App\Services\Order;

use App\Helpers\Amazon\OrderHelper;
use App\Helpers\SnsClient;
use App\Models\Account\Account;
use App\Models\Order\Order;
use App\Models\Order\OrderMetadata;
use App\Services\Customer\CustomerService;

class OrderService
{
    /**
     * Create Order
     *
     * @param Account $account
     * @param array $item
     * @return Order
     */
    public static function create($account, $item)
    {
        // Create Customer
        $customer = CustomerService::create($item);

        if (array_key_exists('AmazonOrderId', $item) || array_key_exists('AmazonOrderID', $item)) {

            // SourceId
            $sourceId = (array_key_exists('AmazonOrderId', $item)) ? $item['AmazonOrderId'] : $item['AmazonOrderID'];

            $order = Order::whereSourceId($sourceId)->first();

            if (empty($order)) {

                // IsActive
                $isActive = ($item['OrderStatus'] == OrderHelper::STATUS_SHIPPED) ? false : true;

                $order = new Order([
                    'account_id'   => $account->id,
                    'customer_id'  => (! empty($customer)) ? $customer->id : null,
                    'source_id'    => $sourceId,
                    'status'       => exists('OrderStatus', $item),
                    'type'         => exists('OrderType', $item),
                    'channel'      => exists('SalesChannel', $item),
                    'total'        => amount(exists('OrderTotal', $item)),
                    'is_completed' => $isActive,
                ]);

                // Save Order
                $order->save();

                debug('Order Created: ' . $order->id);

                // SNS: New Order
                SnsClient::order('new', $order->id);
            }

            // Create Metadata
            self::metadata($order, $item);

            return $order;
        }

        return false;
    }

    /**
     * Update Order
     *
     * @param Order $order
     * @param array $item
     * @return Order
     */
    public static function update($order, $item)
    {
        // Create Customer
        $customer = CustomerService::create($item);

        if (! empty($customer)) {
            $order->customer_id = $customer->id;
        }

        // SourceId
        $order->source_id = $item['AmazonOrderId'];

        // Status
        $order->status = $item['OrderStatus'];

        // Type
        $order->type = $item['OrderType'];

        // Channel
        $order->channel = exists('SalesChannel', $item);

        // Total
        $order->total = amount(exists('OrderTotal', $item));

        // IsCompleted
        $order->is_completed = ($order->status == OrderHelper::STATUS_SHIPPED) ? false : true;

        // Save Order
        $order->save();

        // Create OrderMetadata
        self::metadata($order, $item);

        debug('Order Updated: ' . $order->id);

        // SNS: Updated Order
        SnsClient::order('updated', $order->id);

        return $order;
    }

    /**
     * Create OrderMetadata
     *
     * @param Order $order
     * @param array $item
     */
    protected static function metadata($order, $item)
    {
        $data = [];
        $metadata = $order->metadata->keyBy('meta_key')->all();
        foreach ($item as $key => $value) {
            if (! in_array($key, OrderHelper::BLACKLISTED_FIELDS)) {
                $key = meta_key($key);
                $value = meta_value($value);

                if (array_key_exists($key, $metadata)) {
                    $object = $metadata[$key];
                    if ($object->meta_value != $value) {
                        $object->meta_value = $value;
                        $object->save();
                    }
                }
                else {
                    $data[] = [
                        'order_id'   => $order->id,
                        'meta_key'   => $key,
                        'meta_value' => $value,
                    ];
                }
            }
        }

        // Save OrderMetadata
        if (! empty($data)) {
            OrderMetadata::insert($data);
        }
    }

    /**
     * Set Order as completed
     *
     * @param Order $order
     */
    public static function completed($order)
    {
        $order->is_completed = true;
        $order->save();
    }
}