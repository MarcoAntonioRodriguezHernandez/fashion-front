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

    'discordbot' => [
        'channel_id' => env('DISCORDBOT_CHANNEL_ID', ''),
        'token' => env('DISCORDBOT_TOKEN', ''),
        'endpoint' => env('DISCORDBOT_ENDPOINT', ''),
    ],

    'maps' => [
        'api_key' => env('GOOGLE_MAPS_API_KEY', ''),
    ],

    'conspiracy' => [
        'api_base_url' => env('CONSPIRACY_API_BASE_URL'),
        'sync_enabled' => env('SYNC_ENABLED', false),
    ],

    'marketplaces' => [
        'dynamic_sync_enabled' => env('MARKETPLACE_DYNAMIC_SYNC_ENABLED', true),
        'api_base_url' => env('MARKETPLACE_API_BASE_URL'),
        'version' => env('MARKETPLACE_VERSION'),
    ],

];
