<?php
namespace Automa\WhatsApp;

use Automa\WhatsApp\Console\InstallCommand;
use Automa\WhatsApp\Services\ConversationManager;
use Automa\WhatsApp\Services\FlowEngine;
use Automa\WhatsApp\Services\MediaService;
use Automa\WhatsApp\Services\MessageService;
use Automa\WhatsApp\Services\TemplateService;
use Automa\WhatsApp\Services\WhatsAppClient;
use Illuminate\Support\ServiceProvider;

class WhatsAppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/whatsapp.php', 'whatsapp');
        $this->app->singleton(WhatsAppClient::class);
        $this->app->singleton(MessageService::class);
        $this->app->singleton(MediaService::class);
        $this->app->singleton(TemplateService::class);
        $this->app->singleton(ConversationManager::class);
        $this->app->singleton(FlowEngine::class);
        $this->app->alias(MessageService::class, 'whatsapp');
    }

    public function boot(): void
    {
        $this->publishes([__DIR__.'/../config/whatsapp.php' => config_path('whatsapp.php')], 'whatsapp-config');
        $this->publishes([__DIR__.'/../database/migrations' => database_path('migrations')], 'whatsapp-migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/whatsapp.php');
        if ($this->app->runningInConsole()) $this->commands([InstallCommand::class]);
    }
}
