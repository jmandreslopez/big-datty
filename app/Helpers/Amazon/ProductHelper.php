<?php namespace App\Helpers\Amazon;

class ProductHelper
{
    /*
     |--------------------------------------------------------------------------
     | PRODUCT API PARAMETERS
     |--------------------------------------------------------------------------
     */

    const API_PARAMETERS = [
        'section' => '/Orders/2013-09-01',
        'version' => '2013-09-01',
    ];

    /*
     |--------------------------------------------------------------------------
     | PRODUCT ID TYPES
     |--------------------------------------------------------------------------
     |
     | The product-id-type is a numerical entry that indicates if the
     | product-id is an ASIN, ISBN, UPC, or EAN. This is a required
     | field if product-id is provided.
     |
     */

    const ID_TYPE_ASIN  = '1';
    const ID_TYPE_ISBN  = '2';
    const ID_TYPE_UPC   = '3';
    const ID_TYPE_EAN   = '4';
    const ID_TYPE_OTHER = '5';

    /*
     |--------------------------------------------------------------------------
     | ITEM CONDITION
     |--------------------------------------------------------------------------
     |
     | This is a numeric entry that indicates the condition of
     | the item. This field is required for creating a new listing.
     |
     */

    const CONDITION_USED_LIKE_NEW          = '1';
    const CONDITION_USED_VERY_GOOD         = '2';
    const CONDITION_USED_GOOD              = '3';
    const CONDITION_USED_ACCEPTABLE        = '4';
    const CONDITION_COLLECTIBLE_LIKE_NEW   = '5';
    const CONDITION_COLLECTIBLE_VERY_GOOD  = '6';
    const CONDITION_COLLECTIBLE_GOOD       = '7';
    const CONDITION_COLLECTIBLE_ACCEPTABLE = '8';
    const CONDITION_NOT_USED               = '9';
    const CONDITION_REFURBISHED            = '10';
    const CONDITION_NEW                    = '11';

    /*
     |--------------------------------------------------------------------------
     | WILL SHIP INTERNATIONALLY
     |--------------------------------------------------------------------------
     |
     | A code that indicates the countries to which you are willing to ship
     |
     */

    const SHIP_ONLY_US_CUSTOMERS       = '1';
    const SHIP_CUSTOMERS_IN_AND_OUT_US = '2';

    /*
     |--------------------------------------------------------------------------
     | BLACKLISTED FIELDS
     |--------------------------------------------------------------------------
     */

    const BLACKLISTED_FIELDS = [
        'Title',
        'ASIN',
        'SellerSKU',
        'OrderItemId',
        'QuantityOrdered',
        'ItemPrice',
        'ItemTax',
        'asin1',
        'asin2',
        'asin3',
        'seller-sku',
        'item-name',
        'price',
        'quantity',
    ];
}