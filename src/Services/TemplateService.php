<?php
namespace Automa\WhatsApp\Services;
class TemplateService
{
    public function __construct(protected WhatsAppClient $client) {}
    public function send(string $to,string $name,string $language='en_US',array $components=[]){ return app(MessageService::class)->template($to,$name,$language,$components); }
    public function list(array $query=[]): array { return $this->client->get(config('whatsapp.business_account_id').'/message_templates',$query)->json(); }
}
