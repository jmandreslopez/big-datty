<?php

return [

    /*
    |--------------------------------------------------------------------------
    | GENERAL
    |--------------------------------------------------------------------------
    */

    'general' => [
        'memory_limit' => '1024M', // MB
    ],

    /*
    |--------------------------------------------------------------------------
    | GUZZLE
    |--------------------------------------------------------------------------
    */

    'guzzle' => [
        'retry' => [
            'status_codes' => [
                400, // Bad Request
                500, // Internal Server Error
                503, // Service Unavailable
            ],
            'max' => 5,
        ],
        'pool_size'  => 10,
        'debug_http' => env('APP_DEBUG_HTTP', false),
        'headers' => [
            'User-Agent' => 'BigDatty/1.0',
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | TOR
    |--------------------------------------------------------------------------
    */

    'crawler' => [
        'control' => [
            'hostname'   => env('TOR_SERVER'),
            'port'       => env('TOR_CONTROL_PORT'),
            'password'   => env('TOR_CONTROL_PASSWORD'),
            'authmethod' => \TorControl\TorControl::AUTH_METHOD_HASHEDPASSWORD
        ],
        'request' => [
            'proxy'           => env('TOR_PROXY'),
            'timeout'         => 60,
            'connect_timeout' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | AMAZON AWS
    |--------------------------------------------------------------------------
    */

    'amazon' => [
        'sns' => [
            'topics' => [
                'accounts'  => 'arn:aws:sns:us-east-1:197341827079:bigdatty-accounts',
                'seller'    => 'arn:aws:sns:us-east-1:197341827079:bigdatty-sellers',
                'orders'    => 'arn:aws:sns:us-east-1:197341827079:bigdatty-orders',
                'products'  => 'arn:aws:sns:us-east-1:197341827079:bigdatty-products',
                'feedbacks' => 'arn:aws:sns:us-east-1:197341827079:bigdatty-feedback',
                'reviews'   => 'arn:aws:sns:us-east-1:197341827079:bigdatty-reviews',
                'questions' => 'arn:aws:sns:us-east-1:197341827079:bigdatty-questions',
                'hijacks'   => 'arn:aws:sns:us-east-1:197341827079:bigdatty-hijacks',
            ],
        ],
        'sqs' => [
            'next-tokens'        => 'https://sqs.us-east-1.amazonaws.com/197341827079/bigdatty-next-tokens',
            'orders'             => 'https://sqs.us-east-1.amazonaws.com/197341827079/bigdatty-orders',
            'orders-pending'     => 'https://sqs.us-east-1.amazonaws.com/197341827079/bigdatty-orders-pending',
            'products'           => 'https://sqs.us-east-1.amazonaws.com/197341827079/bigdatty-products',
            'products-reviews'   => 'https://sqs.us-east-1.amazonaws.com/197341827079/bigdatty-products-reviews',
            'products-questions' => 'https://sqs.us-east-1.amazonaws.com/197341827079/bigdatty-products-questions',
            'feedbacks'          => 'https://sqs.us-east-1.amazonaws.com/197341827079/bigdatty-feedbacks',
            'reports'            => 'https://sqs.us-east-1.amazonaws.com/197341827079/bigdatty-reports',
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | ACCOUNTS
    |--------------------------------------------------------------------------
    */

    'accounts' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | ORDERS
    |--------------------------------------------------------------------------
    */

    'orders' => [
        'length' => '1', // hours
        'pending' => [
            'delay' => 900, // 15 minutes
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | PRODUCTS
    |--------------------------------------------------------------------------
    */

    'products' => [
        'reviews' => [
            'length' => '7', // days
        ],
        'questions' => [
            'length' => '30', // days
        ],
        'hijacks' => [
            //
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | FEEDBACKS
    |--------------------------------------------------------------------------
    */

    'feedbacks' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | REPORTS
    |--------------------------------------------------------------------------
    */

    'reports' => [
        'delay'  => 900, // 15 minutes
        'length' => '7', // days
        'types'  => [
            '_GET_SELLER_FEEDBACK_DATA_',
            //'_GET_AMAZON_FULFILLED_SHIPMENTS_DATA_',
            //'_GET_FLAT_FILE_ALL_ORDERS_DATA_BY_LAST_UPDATE_',
            //'_GET_XML_ALL_ORDERS_DATA_BY_LAST_UPDATE_',
            //'_GET_MERCHANT_LISTINGS_DATA_',
        ],
    ],
];