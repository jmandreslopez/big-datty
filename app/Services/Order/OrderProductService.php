<?php namespace App\Services\Order;

use App\Helpers\Amazon\ProductHelper;
use App\Models\Order\Order;
use App\Models\Order\OrderProduct;
use App\Models\Order\OrderProductMetadata;
use App\Models\Product\Product;

class OrderProductService
{
    /**
     * Create OrderProduct
     *
     * @param Order $order
     * @param Product $product
     * @param array $item
     * @return OrderProduct
     */
    public static function create($order, $product, $item)
    {
        // SourceId
        $sourceId = $item['OrderItemId'];

        // Quantity
        $quantity =  (array_key_exists('QuantityOrdered', $item)) ? $item['QuantityOrdered'] : 1;

        // Price
        $price = (array_key_exists('ItemPrice', $item)) ?
            self::price($quantity, $item['ItemPrice']) :
            null;

        $orderProduct = new OrderProduct([
            'order_id'   => $order->id,
            'product_id' => $product->id,
            'source_id'  => $sourceId,
            'quantity'   => $quantity,
            'price'      => $price,
        ]);

        // Save OrderProduct
        $orderProduct->save();

        // Create Metadata
        self::metadata($orderProduct, $item);

        debug('OrderProduct Created: ' . $orderProduct->id);

        return $orderProduct;
    }

    /**
     * Get Item Price
     *
     * @param int $quantity
     * @param int $item
     * @return null|int
     */
    protected static function price($quantity, $item)
    {
        if (! empty($item)) {
            $price = amount($item);
            if (! empty($price)) {

                return decimals($price / $quantity);
            }
        }

        return null;
    }

    /**
     * Create Metadata
     *
     * @param OrderProduct $orderProduct
     * @param array $item
     */
    protected static function metadata($orderProduct, $item)
    {
        $data = [];
        $metadata = $orderProduct->metadata->keyBy('meta_key')->all();
        foreach ($item as $key => $value) {
            if (! in_array($key, ProductHelper::BLACKLISTED_FIELDS)) {
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
                        'order_product_id' => $orderProduct->id,
                        'meta_key'         => $key,
                        'meta_value'       => $value,
                    ];
                }
            }
        }

        // Save OrderProductMetadata
        if (!empty($data)) {
            OrderProductMetadata::insert($data);
        }
    }
}