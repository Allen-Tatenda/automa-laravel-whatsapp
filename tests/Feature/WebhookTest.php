<?php
namespace Automa\WhatsApp\Tests\Feature;

use Automa\WhatsApp\Tests\TestCase;
use Illuminate\Support\Facades\Http;

class WebhookTest extends TestCase
{
    public function test_webhook_verification(): void
    {
        config(['whatsapp.verify_token'=>'verify']);
        $this->get('/whatsapp/webhook?hub.mode=subscribe&hub.verify_token=verify&hub.challenge=123')
            ->assertOk()->assertSee('123');
    }

    public function test_webhook_acknowledges_and_persists_message(): void
    {
        Http::fake();
        config(['whatsapp.process_webhooks_async'=>false]);
        $payload=['entry'=>[['changes'=>[['value'=>[
            'messages'=>[['id'=>'wamid.test','from'=>'263771234567','type'=>'text','text'=>['body'=>'hello']]],
            'contacts'=>[['profile'=>['name'=>'Test']]],
        ]]]]]];
        $this->postJson('/whatsapp/webhook',$payload)->assertOk()->assertJson(['received'=>true]);
        $this->assertDatabaseHas('whatsapp_contacts',['wa_id'=>'263771234567']);
        $this->assertDatabaseHas('whatsapp_messages',['wamid'=>'wamid.test','text'=>'hello']);
    }
}
