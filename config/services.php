<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Gateway Webhooks
    |--------------------------------------------------------------------------
    |
    | Per-gateway secret used to verify the HMAC-SHA256 signature on inbound
    | webhook/IPN calls (see PaymentWebhookController). Add a key named after
    | the gateway (matching the {gateway} route segment) once a real provider
    | is integrated; 'default' is used when no gateway-specific entry exists.
    |
    */

    'payment_webhooks' => [
        'default' => [
            'secret' => env('PAYMENT_WEBHOOK_SECRET'),
            'signature_header' => 'X-SePay-Signature',
            'signature_timestamp' => 'X-SePay-Timestamp',
            'sepay_merchant_id' => env('SEPAY_MERCHANT_ID'),
            'sepay_merchant_secrete' => env('SEPAY_MERCHANT_SECRET'),
        ],
    ],

];
