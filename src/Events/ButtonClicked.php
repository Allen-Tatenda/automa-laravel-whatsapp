<?php
namespace Automa\WhatsApp\Events;
class ButtonClicked { public function __construct(public array $message, public string $buttonId) {} }
