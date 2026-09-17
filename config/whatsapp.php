<?php

return [

    'api_version' => env(
        'WHATSAPP_API_VERSION',
        'v23.0'
    ),

    'access_token' => env(
        'WHATSAPP_ACCESS_TOKEN'
    ),

    'phone_number_id' => env(
        'WHATSAPP_PHONE_NUMBER_ID'
    ),

    'business_account_id' => env(
        'WHATSAPP_BUSINESS_ACCOUNT_ID'
    ),

    'verify_token' => env(
        'WHATSAPP_VERIFY_TOKEN'
    ),

    'base_url' => env(
        'WHATSAPP_BASE_URL',
        'https://graph.facebook.com'
    ),

    'webhook' => [
        'path' => env(
            'WHATSAPP_WEBHOOK_PATH',
            'whatsapp/webhook'
        ),
    ],

    'queue' => [
        'enabled' => env(
            'WHATSAPP_QUEUE',
            false
        ),

        'connection' => env(
            'WHATSAPP_QUEUE_CONNECTION'
        ),

        'queue' => env(
            'WHATSAPP_QUEUE_NAME',
            'default'
        ),
    ],

];