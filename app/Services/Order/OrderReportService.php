<?php namespace App\Services\Order;

class OrderReportService
{
    protected function create($account, $properties)
    {
//        if (! empty($properties['amazon-order-id'])) {
//            $order = Order::whereSourceId($properties['amazon-order-id'])->first();
//
//            if (empty($order)) {
//                $order = $this->newOrder([
//                    'account_id' => $account->id,
//                    'marketplace_type_id' => MarketplaceType::TYPE_AMAZON,
//                    'source_id' => property($properties['amazon-order-id']),
//                    'status' => property($properties['order-status']),
//                    'channel' => isset($properties['sales-channel']) ? property($properties['sales-channel']) : null,
//                    'is_completed' => ($properties['order-status'] == OrderHelper::STATUS_SHIPPED) ? 0 : 1,
//                ]);
//            }
//
//            // Create Customer
//            $this->createCustomer($order, $properties);
//
//            // Create OrderMetadata
//            $this->createOrderMetadata($order, $properties);
//
//            return $order;
//        }
    }
}