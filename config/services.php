<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */
    'aws' => [
        'access_key' => env('AWS_ACCESS_KEY_ID'),
        'secret_key' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => 'ap-northeast-1',
        'bucket' => [
            'posts' => '29ns-bucket',
        ],
        'url' => [
            'img' => 'https://img.29-ns.com/',
        ],
    ],

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],
    // ぐるなびAPI
    'gnavi' => [
        'key'      => env('GNAVI_KEY'),
        'base_url' => 'https://api.gnavi.co.jp/',
        'api_path' => [
            'restSearch' => 'RestSearchAPI/v3/',
            'erea'       => 'master/GAreaMiddleSearchAPI/v3/',
            'photoSearch' => 'PhotoSearchAPI/v3/'
        ],
    ],

];
