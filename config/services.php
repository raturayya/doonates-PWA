<?php

return [

    'mailgun' => [
        'domain'   => env('MAILGUN_DOMAIN'),
        'secret'   => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme'   => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Anthropic / Claude AI (opsional, jika ingin pakai Anthropic langsung)
    |--------------------------------------------------------------------------
    */
    'anthropic' => [
        'key' => env('ANTHROPIC_API_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | OpenRouter AI (gratis, aktif digunakan)
    |--------------------------------------------------------------------------
    | Daftar di https://openrouter.ai — gratis, tanpa kartu kredit
    | Tambahkan OPENROUTER_API_KEY ke file .env
    */
    'openrouter' => [
        'key' => env('OPENROUTER_API_KEY'),
    ],

];