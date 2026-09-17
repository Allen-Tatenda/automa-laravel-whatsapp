<?php
namespace Automa\WhatsApp\Events;
class MessageReceived { public function __construct(public array $message, public mixed $contact, public mixed $conversation) {} }
