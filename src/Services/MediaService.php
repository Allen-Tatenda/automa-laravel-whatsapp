<?php
namespace Automa\WhatsApp\Services;
class MediaService
{
    public function __construct(protected WhatsAppClient $client) {}
    public function upload(string $path,string $mime): string { return (string)$this->client->uploadMedia($path,$mime)->json('id'); }
    public function get(string $mediaId): array { return $this->client->get($mediaId)->json(); }
    public function downloadUrl(string $mediaId): ?string { return $this->get($mediaId)['url'] ?? null; }
}
