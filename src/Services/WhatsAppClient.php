<?php
namespace Automa\WhatsApp\Services;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;
class WhatsAppClient
{
    public function __construct(protected ?string $token = null) { $this->token ??= config('whatsapp.access_token'); }
    protected function graph(string $path): string { return rtrim(config('whatsapp.base_url'), '/').'/'.trim(config('whatsapp.api_version'), '/').'/'.ltrim($path, '/'); }
    public function request(string $method, string $path, array $data = [], array $headers = []): Response
    {
        $http = Http::withToken($this->token)->acceptJson()->withHeaders($headers);
        $response = $http->$method($this->graph($path), $data);
        if ($response->failed()) throw new RuntimeException('WhatsApp API error: '.$response->body(), $response->status());
        return $response;
    }
    public function send(string $payload): Response { return $this->request('post', config('whatsapp.phone_number_id').'/messages', json_decode($payload, true) ?: []); }
    public function sendMessage(array $payload): Response { return $this->request('post', config('whatsapp.phone_number_id').'/messages', $payload); }
    public function uploadMedia(string $path, string $mime): Response
    {
        $url = $this->graph(config('whatsapp.phone_number_id').'/media');
        $response = Http::withToken($this->token)->attach('file', fopen($path, 'r'), basename($path))->post($url, ['messaging_product' => 'whatsapp', 'type' => $mime]);
        if ($response->failed()) throw new RuntimeException('WhatsApp media upload error: '.$response->body(), $response->status());
        return $response;
    }
    public function get(string $path, array $query = []): Response { return Http::withToken($this->token)->acceptJson()->get($this->graph($path), $query); }
}
