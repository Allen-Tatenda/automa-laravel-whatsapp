<?php
namespace Automa\WhatsApp\Tests\Unit;

use Automa\WhatsApp\Services\ConversationManager;
use Automa\WhatsApp\Services\FlowEngine;
use Automa\WhatsApp\Services\MessageService;
use Automa\WhatsApp\Tests\TestCase;
use Mockery;

class FlowEngineTest extends TestCase
{
    public function test_reply_flow_runs_on_matching_text(): void
    {
        $messages=Mockery::mock(MessageService::class);
        $messages->shouldReceive('text')->once()->with('263771234567','Hello');
        $manager=Mockery::mock(ConversationManager::class);
        $this->app->instance(MessageService::class,$messages);
        $this->app->instance(ConversationManager::class,$manager);
        $conversation=(object)['contact'=>(object)['wa_id'=>'263771234567']];
        app(FlowEngine::class)->run(['nodes'=>[['type'=>'reply','when'=>['field'=>'text','operator'=>'equals','value'=>'hello'],'message'=>'Hello']],['text'=>'hello'],$conversation);
    }
}
