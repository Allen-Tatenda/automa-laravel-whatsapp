<?php

namespace Automa\WhatsApp;

use Automa\WhatsApp\Services\MessageService;
use Automa\WhatsApp\Services\WhatsAppClient;
use Illuminate\Support\ServiceProvider;

class WhatsAppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/whatsapp.php',
            'whatsapp'
        );

        $this->app->singleton(
            WhatsAppClient::class,
            fn () => new WhatsAppClient()
        );

        $this->app->singleton(
            MessageService::class,
            fn ($app) => new MessageService(
                $app->make(WhatsAppClient::class)
            )
        );

        $this->app->alias(
            MessageService::class,
            'whatsapp'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/whatsapp.php' =>
                config_path('whatsapp.php'),
        ], 'whatsapp-config');
    }
}