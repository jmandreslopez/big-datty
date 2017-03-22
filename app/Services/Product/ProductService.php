<?php namespace App\Services\Product;

use App\Helpers\Amazon\ProductHelper;
use App\Helpers\SnsClient;
use App\Models\Account\Account;
use App\Models\Order\Order;
use App\Models\Product\Product;
use App\Models\Product\ProductMetadata;
use App\Services\Order\OrderProductService;

class ProductService
{
    /**
     * Create Product
     *
     * @param Account $account
     * @param Order $order
     * @param array $item
     * @return Product|bool
     */
    public static function create($account, $order, $item)
    {
        if (array_key_exists('ASIN', $item) &&
            array_key_exists('OrderItemId', $item) &&
            $item['OrderItemId'] != '0') {

            // Get Product
            $product = Product::whereMarketplaceId($account->marketplace->id)
                ->whereSourceId($item['ASIN'])->first();

            if (empty($product)) {
                $product = new Product([
                    'account_id'     => $account->id,
                    'marketplace_id' => $account->marketplace->id,
                    'source_id'      => $item['ASIN'],
                    'sku'            => exists('SellerSKU', $item),
                    'name'           => exists('Title', $item),
                ]);

                // Save Product
                $product->save();

                debug('Product Created: ' . $product->id);

                // Publish Product
                SnsClient::product('new', $product->id);
            }

            // Create OrderProduct
            OrderProductService::create($order, $product, $item);

            return $product;
        }

        return false;
    }

    /**
     * Create ProductMetadata
     *
     * @param Product $product
     * @param array $item
     */
    protected static function metadata($product, $item)
    {
        $data = [];
        $metadata = $product->metadata->keyBy('meta_key')->all();
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
                        'product_id' => $product->id,
                        'meta_key'   => $key,
                        'meta_value' => $value,
                    ];
                }
            }
        }

        // Save ProductMetadata
        if (! empty($data)) {
            ProductMetadata::insert($data);
        }
    }

    /**
     * Update total Reviews
     *
     * @param Product $product
     * @param int $total
     */
    public static function updateReviews($product, $total)
    {
        $product->total_reviews = $total;
        $product->save();
    }

    /**
     * Update total Questions
     *
     * @param Product $product
     * @param int $total
     */
    public static function updateQuestions($product, $total)
    {
        $product->total_questions = $total;
        $product->save();
    }
}