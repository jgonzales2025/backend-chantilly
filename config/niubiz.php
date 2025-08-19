<?php

return [
    'pro' => env('NIUBIZ_PRO', false),
    'user' => env('NIUBIZ_USER'),
    'password' => env('NIUBIZ_PASSWORD'),
    'merchant_id' => env('NIUBIZ_MERCHANT_ID'),

    'urls' => [
        'security' => [
            'sandbox' => 'https://apisandbox.vnforappstest.com/api.security/v1/security',
            'prod' => 'https://apiprod.vnforapps.com/api.security/v1/security',
        ],
        'session' => [
            'sandbox' => 'https://apisandbox.vnforappstest.com/api.ecommerce/v2/ecommerce/token/session/',
            'prod' => 'https://apiprod.vnforapps.com/api.ecommerce/v2/ecommerce/token/session/',
        ],
        'transaction' => [
            'sandbox' => 'https://apisandbox.vnforappstest.com/api.authorization/v3/authorization/ecommerce/',
            'prod' => 'https://apiprod.vnforapps.com/api.authorization/v3/authorization/ecommerce/',
        ],
        'checkout_js' => [
            'sandbox' => 'https://static-content-qas.vnforapps.com/v2/js/checkout.js?qa=true',
            'prod' => 'https://static-content.vnforapps.com/v2/js/checkout.js',
        ],
    ]
];
