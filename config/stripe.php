<?php



return [
    'key'            => env('STRIPE_KEY'),
    'secret'         => env('STRIPE_SECRET'),
    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    'prices' => [
        'basico'      => env('STRIPE_PRICE_BASICO'),
        'profesional' => env('STRIPE_PRICE_PROFESIONAL'),
        'empresarial' => env('STRIPE_PRICE_EMPRESARIAL'),
    ],
];