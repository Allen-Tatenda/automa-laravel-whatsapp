<?php
use Automa\WhatsApp\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;
Route::middleware(config('whatsapp.route_middleware', ['api']))->group(function(){
    Route::get(config('whatsapp.webhook_path','whatsapp/webhook'),[WebhookController::class,'verify'])->name('whatsapp.webhook.verify');
    Route::post(config('whatsapp.webhook_path','whatsapp/webhook'),[WebhookController::class,'receive'])->name('whatsapp.webhook.receive');
});
