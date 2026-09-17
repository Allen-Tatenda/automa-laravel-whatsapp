# Automa Laravel WhatsApp

A Composer-installable Laravel WhatsApp Cloud API automation engine. It provides Meta Graph API integration, verified webhooks, persistent contacts/conversations/messages, state and context, interactive messages, templates, media, events, queues, bots and a JSON flow engine.

## Requirements

- PHP 8.2+
- Laravel 11, 12 or 13
- Meta WhatsApp Cloud API credentials

## Install

```bash
composer require automa/laravel-whatsapp
php artisan whatsapp:install
php artisan migrate
```

Configure `.env`:

```env
WHATSAPP_ACCESS_TOKEN=
WHATSAPP_PHONE_NUMBER_ID=
WHATSAPP_BUSINESS_ACCOUNT_ID=
WHATSAPP_VERIFY_TOKEN=
WHATSAPP_APP_SECRET=
WHATSAPP_API_VERSION=v23.0
WHATSAPP_WEBHOOK_PATH=whatsapp/webhook
WHATSAPP_PROCESS_WEBHOOKS_ASYNC=true
WHATSAPP_QUEUE_CONNECTION=database
WHATSAPP_QUEUE_NAME=default
WHATSAPP_DEFAULT_BOT=
```

Set your Meta webhook callback URL to `/whatsapp/webhook`, subscribe to `messages`, and use the verify token above.

## Sending messages

```php
use Automa\WhatsApp\Facades\WhatsApp;

WhatsApp::text('263771234567', 'Hello!');
WhatsApp::buttons('263771234567', 'Choose an option', [
    ['id' => 'products', 'title' => 'Products'],
    ['id' => 'support', 'title' => 'Support'],
]);
WhatsApp::list('263771234567', 'Choose a department', [
    ['title' => 'Departments', 'rows' => [
        ['id' => 'sales', 'title' => 'Sales'],
        ['id' => 'support', 'title' => 'Support'],
    ]],
]);
WhatsApp::template('263771234567', 'welcome', 'en_US');
```

The facade resolves `MessageService`. For media operations use `MediaService`, for templates use `TemplateService`, for conversation state use `ConversationManager`.

## Persistent state

```php
$conversationManager->put($conversation, 'customer.name', 'Allen');
$name = $conversationManager->state($conversation, 'customer.name');
$conversationManager->putContext($conversation, 'cart.total', 120);
```

## Bots

Implement `Automa\WhatsApp\Contracts\Bot`, register the class in `whatsapp.bots`, and set `WHATSAPP_DEFAULT_BOT`.

## JSON flows

`FlowEngine` supports `reply`, `buttons`, `list`, `template`, `set_state`, and `set_context` nodes with `equals`, `contains`, and `exists` conditions. Example:

```php
$flow = [
  'nodes' => [
    ['type'=>'reply', 'when'=>['field'=>'text','operator'=>'equals','value'=>'hello'], 'message'=>'Hello {{ state.customer.name }}!'],
    ['type'=>'buttons', 'body'=>'Choose one', 'buttons'=>[
      ['id'=>'products','title'=>'Products'],
      ['id'=>'support','title'=>'Support'],
    ]],
  ],
];
```

## Events

- `MessageReceived`
- `MessageSent`
- `ConversationStarted`
- `ButtonClicked`

## Security

If `WHATSAPP_APP_SECRET` is configured, POST webhooks are checked against `X-Hub-Signature-256`. Webhook verification uses the configured verify token.

## Tests

```bash
composer install
vendor/bin/phpunit
```

## Publishing

Push this repository to GitHub/GitLab, create a tagged release, and register `automa/laravel-whatsapp` on Packagist. Consumers can then install it with `composer require automa/laravel-whatsapp`.
