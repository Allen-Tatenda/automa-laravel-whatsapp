<?php

namespace Automa\WhatsApp\Services;

class MessageService
{
    public function __construct(
        protected WhatsAppClient $client
    ) {}

    public function text(
        string $to,
        string $body,
        bool $previewUrl = false
    ) {
        return $this->client->sendMessage([
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'text',
            'text' => [
                'preview_url' => $previewUrl,
                'body' => $body,
            ],
        ]);
    }

    public function image(
        string $to,
        string $url,
        ?string $caption = null
    ) {
        $image = [
            'link' => $url,
        ];

        if ($caption) {
            $image['caption'] = $caption;
        }

        return $this->client->sendMessage([
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'image',
            'image' => $image,
        ]);
    }

    public function document(
        string $to,
        string $url,
        ?string $filename = null
    ) {
        $document = [
            'link' => $url,
        ];

        if ($filename) {
            $document['filename'] = $filename;
        }

        return $this->client->sendMessage([
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'document',
            'document' => $document,
        ]);
    }

    public function buttons(
        string $to,
        string $body,
        array $buttons
    ) {
        return $this->client->sendMessage([
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'interactive',
            'interactive' => [
                'type' => 'button',
                'body' => [
                    'text' => $body,
                ],
                'action' => [
                    'buttons' => array_map(
                        fn ($button) => [
                            'type' => 'reply',
                            'reply' => [
                                'id' => $button['id'],
                                'title' => $button['title'],
                            ],
                        ],
                        $buttons
                    ),
                ],
            ],
        ]);
    }
} 