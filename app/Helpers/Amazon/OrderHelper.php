<?php namespace App\Helpers\Amazon;

class OrderHelper
{
    /*
     |--------------------------------------------------------------------------
     | ORDER API PARAMETERS
     |--------------------------------------------------------------------------
     */

    const API_PARAMETERS = [
        'section' => '/Orders/2013-09-01',
        'version' => '2013-09-01',
    ];

    /*
     |--------------------------------------------------------------------------
     | ORDER STATUS
     |--------------------------------------------------------------------------
     */

    const STATUS_PENDING              = 'Pending';
    const STATUS_PENDING_AVAILABILITY = 'PendingAvailability';
    const STATUS_UNSHIPPED            = 'Unshipped';
    const STATUS_PARTIALLY_SHIPPED    = 'PartiallyShipped';
    const STATUS_SHIPPED              = 'Shipped';
    const STATUS_CANCELED             = 'Canceled';

    /*
     |--------------------------------------------------------------------------
     | ORDER TYPES
     |--------------------------------------------------------------------------
     */

    const TYPE_STANDARD = 'StandardOrder';
    const TYPE_PREORDER = 'Preorder';       // JP only

    /*
     |--------------------------------------------------------------------------
     | BLACKLISTED FIELDS
     |--------------------------------------------------------------------------
     */

    const BLACKLISTED_FIELDS = [
        'AmazonOrderId',
        'OrderStatus',
        'OrderType',
        'SalesChannel',
        'OrderTotal',
        'BuyerEmail',
        'BuyerName',
        'OrderItem',
    ];
}