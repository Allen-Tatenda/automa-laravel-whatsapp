<?php
namespace Automa\WhatsApp\Events;
class MessageSent { public function __construct(public string $to, public array $payload, public array $response = []) {} }
