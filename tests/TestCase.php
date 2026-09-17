<?php
namespace Automa\WhatsApp\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Schema;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app){ return [\Automa\WhatsApp\WhatsAppServiceProvider::class]; }
    protected function getEnvironmentSetUp($app){
        $app['config']->set('database.default','testing');
        $app['config']->set('database.connections.testing',['driver'=>'sqlite','database'=>':memory:','prefix'=>'']);
        $app['config']->set('whatsapp.process_webhooks_async',false);
    }
    protected function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
