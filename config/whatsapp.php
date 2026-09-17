<?php
return [
    'api_version' => env('WHATSAPP_API_VERSION', 'v23.0'),
    'access_token' => env('WHATSAPP_ACCESS_TOKEN'),
    'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
    'business_account_id' => env('WHATSAPP_BUSINESS_ACCOUNT_ID'),
    'verify_token' => env('WHATSAPP_VERIFY_TOKEN'),
    'app_secret' => env('WHATSAPP_APP_SECRET'),
    'base_url' => env('WHATSAPP_BASE_URL', 'https://graph.facebook.com'),
    'webhook_path' => env('WHATSAPP_WEBHOOK_PATH', 'whatsapp/webhook'),
    'route_middleware' => ['api'],
    'process_webhooks_async' => env('WHATSAPP_PROCESS_WEBHOOKS_ASYNC', true),
    'queue' => ['connection' => env('WHATSAPP_QUEUE_CONNECTION'), 'name' => env('WHATSAPP_QUEUE_NAME', 'default')],
    'database' => ['connection' => env('WHATSAPP_DB_CONNECTION')],
    'models' => [
        'contact' => Automa\WhatsApp\Models\WhatsAppContact::class,
        'conversation' => Automa\WhatsApp\Models\WhatsAppConversation::class,
        'message' => Automa\WhatsApp\Models\WhatsAppMessage::class,
    ],
    'default_bot' => env('WHATSAPP_DEFAULT_BOT'),
    'bots' => [],
];
