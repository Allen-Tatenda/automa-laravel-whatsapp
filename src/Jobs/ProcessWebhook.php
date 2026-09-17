<?php
namespace Automa\WhatsApp\Jobs;

use Automa\WhatsApp\Http\Controllers\WebhookController;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessWebhook implements ShouldQueue
{
    public function __construct(public array $payload) {}
    public function handle(WebhookController $controller): void { $controller->process($this->payload); }
}
