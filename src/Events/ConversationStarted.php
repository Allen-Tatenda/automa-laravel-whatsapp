<?php
namespace Automa\WhatsApp\Events;
class ConversationStarted { public function __construct(public mixed $conversation) {} }
