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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'tokopay' => [
        'base_url' => env('TOKOPAY_BASE_URL', 'https://api.tokopay.id'), // sesuaikan
        'api_key'  => env('TOKOPAY_API_KEY'),
        'timeout'  => env('TOKOPAY_TIMEOUT', 20),
    ],

    'digiflazz' => [
        'base_url' => env('DIGIFLAZZ_BASE_URL', 'https://api.digiflazz.com'),
        'username' => env('DIGIFLAZZ_USERNAME'),
        'api_key'  => env('DIGIFLAZZ_API_KEY'),
        'timeout'  => env('DIGIFLAZZ_TIMEOUT', 25),
    ],

    'whacenter' => [
    'device_id' => env('WHACENTER_DEVICE_ID'),
    ],

    'telegram' => [
        'bot_token' => env('TELEGRAM_BOT_TOKEN'),
        'timeout'   => env('TELEGRAM_TIMEOUT', 15),
        'admin_chat_id'   => env('TELEGRAM_ADMIN_CHAT_ID'),
    ],

    'midtrans' => [
        'server_key' => env('MIDTRANS_SERVER_KEY'),
        'client_key' => env('MIDTRANS_CLIENT_KEY'),
        'merchant_id' => env('MIDTRANS_MERCHANT_ID'),
        'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
        'is_sanitized' => true,
        'is_3ds' => true,
    ],


];
