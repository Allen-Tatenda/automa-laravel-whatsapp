<?php
namespace Automa\WhatsApp\Contracts;

interface Bot
{
    public function handle(array $message, \Automa\WhatsApp\Services\ConversationManager $conversation): void;
}
