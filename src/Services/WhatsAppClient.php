<?php

namespace Automa\WhatsApp\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class WhatsAppClient
{
    protected string $baseUrl;

    protected string $apiVersion;

    protected string $accessToken;

    protected string $phoneNumberId;

    public function __construct()
    {
        $this->baseUrl = config('whatsapp.base_url');

        $this->apiVersion = config(
            'whatsapp.api_version'
        );

        $this->accessToken = config(
            'whatsapp.access_token'
        );

        $this->phoneNumberId = config(
            'whatsapp.phone_number_id'
        );
    }

    protected function url(string $endpoint): string
    {
        return sprintf(
            '%s/%s/%s/%s',
            $this->baseUrl,
            $this->apiVersion,
            $this->phoneNumberId,
            ltrim($endpoint, '/')
        );
    }

    public function post(
        string $endpoint,
        array $data
    ): Response {
        $response = Http::withToken(
            $this->accessToken
        )->acceptJson()->post(
            $this->url($endpoint),
            $data
        );

        if ($response->failed()) {
            throw new RuntimeException(
                'WhatsApp API error: '.$response->body()
            );
        }

        return $response;
    }

    public function sendMessage(array $message): Response
    {
        return $this->post('messages', $message);
    }
}