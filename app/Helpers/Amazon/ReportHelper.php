<?php namespace App\Helpers\Amazon;

class ReportHelper
{
    /*
     |--------------------------------------------------------------------------
     | REPORT API PARAMETERS
     |--------------------------------------------------------------------------
     */

    const API_PARAMETERS = [
        'section' => '/Reports/2009-01-01',
        'version' => '2009-01-01',
    ];

    /*
     |--------------------------------------------------------------------------
     | REPORT STATUS
     |--------------------------------------------------------------------------
     */

    const STATUS_SUBMITTED    = '_SUBMITTED_';
    const STATUS_IN_PROGRESS  = '_IN_PROGRESS_';
    const STATUS_CANCELLED    = '_CANCELLED_';
    const STATUS_DONE         = '_DONE_';
    const STATUS_DONE_NO_DATA = '_DONE_NO_DATA_';
    const STATUS_ACKNOWLEDGED = '_ACKNOWLEDGED_';

    // Status marked as Completed
    const COMPLETED_STATUS = [
        self::STATUS_DONE,
        self::STATUS_DONE_NO_DATA,
        self::STATUS_CANCELLED,
        self::STATUS_ACKNOWLEDGED,
    ];

    /*
     |--------------------------------------------------------------------------
     | REPORT TYPES
     |--------------------------------------------------------------------------
     */

    const TYPES = [
        'Listings' => [
            '_GET_FLAT_FILE_OPEN_LISTINGS_DATA_',
            '_GET_MERCHANT_LISTINGS_DATA_',
        ],
        'Orders' => [
            '_GET_ORDERS_DATA_',
            '_GET_FLAT_FILE_ORDERS_DATA_',
            '_GET_FLAT_FILE_ALL_ORDERS_DATA_BY_LAST_UPDATE_',
            '_GET_FLAT_FILE_ALL_ORDERS_DATA_BY_ORDER_DATE_',
            '_GET_FLAT_FILE_PENDING_ORDERS_DATA_',
            '_GET_XML_ALL_ORDERS_DATA_BY_LAST_UPDATE_',
            '_GET_XML_ALL_ORDERS_DATA_BY_ORDER_DATE_',
            '_GET_PENDING_ORDERS_DATA_',
        ],
        'Performance' => [
            '_GET_SELLER_FEEDBACK_DATA_',
        ],
        'Settlements' => [
            '_GET_V2_SETTLEMENT_REPORT_DATA_FLAT_FILE_',
            '_GET_V2_SETTLEMENT_REPORT_DATA_XML_',
        ],
        'FBA' => [
            'Sales' => [
                '_GET_AMAZON_FULFILLED_SHIPMENTS_DATA_',
            ],
            'Inventory' => [
                '_GET_AFN_INVENTORY_DATA_',
            ],
            'Customer' => [
                '_GET_FBA_FULFILLMENT_CUSTOMER_RETURNS_DATA_',
            ]
        ]
    ];

    /*
     |--------------------------------------------------------------------------
     | ASYNC REPORT TYPES
     |--------------------------------------------------------------------------
     |
     | Report Types that have to be requested using RequestReport and later GetReport
     |
     */

    //const ASYNC_REPORT_TYPES = config('bigdatty.reports.types');

    /*
     |--------------------------------------------------------------------------
     | SYNC REPORT TYPES
     |--------------------------------------------------------------------------
     |
     | Report Types that have to be requested using GetReport directly
     |
     */

    const SYNC_REPORT_TYPES = [
        '_GET_V2_SETTLEMENT_REPORT_DATA_FLAT_FILE_'
    ];
}