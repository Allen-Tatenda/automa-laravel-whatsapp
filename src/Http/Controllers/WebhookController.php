<?php
namespace Automa\WhatsApp\Http\Controllers;
use Automa\WhatsApp\Events\ButtonClicked;
use Automa\WhatsApp\Events\MessageReceived;
use Automa\WhatsApp\Services\ConversationManager;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Event;
use Automa\WhatsApp\Jobs\ProcessWebhook;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
class WebhookController extends Controller
{
    public function verify(Request $request)
    {
        abort_unless(in_array($request->query('hub.mode', $request->query('hub_mode')), ['subscribe'], true) && hash_equals((string)config('whatsapp.verify_token'),(string)$request->query('hub.verify_token', $request->query('hub_verify_token'))),403);
        return response($request->query('hub.challenge', $request->query('hub_challenge')),200);
    }
    public function receive(Request $request)
    {
        if (config('whatsapp.app_secret')) {
            $signature=$request->header('X-Hub-Signature-256',''); $expected='sha256='.hash_hmac('sha256',$request->getContent(),config('whatsapp.app_secret'));
            abort_unless(hash_equals($expected,$signature),403);
        }
        $payload=$request->all();
        if (config('whatsapp.process_webhooks_async')) dispatch(new ProcessWebhook($payload)); else $this->process($payload);
        return response()->json(['received'=>true]);
    }
    public function process(array $payload): void
    {
        foreach (data_get($payload,'entry',[]) as $entry) foreach (data_get($entry,'changes',[]) as $change) {
            foreach (data_get($change,'value.messages',[]) as $message) {
                $waId=$message['from'] ?? null; if (!$waId) continue;
                $profile=data_get($change,'value.contacts.0',[]);
                $conversation=app(ConversationManager::class)->start($waId,['name'=>data_get($profile,'profile.name')]);
                app(ConversationManager::class)->recordIncoming($conversation,$message);
                Event::dispatch(new MessageReceived($message,$conversation->contact,$conversation));
                if (isset($message['interactive']['button_reply']['id'])) Event::dispatch(new ButtonClicked($message,$message['interactive']['button_reply']['id']));
                $bot=$this->resolveBot($message,$conversation);
                if ($bot) app($bot)->handle($message,$conversation);
            }
        }
    }
    protected function resolveBot(array $message,$conversation): ?string
    {
        $name=config('whatsapp.default_bot');
        return $name ? (config('whatsapp.bots')[$name] ?? $name) : null;
    }
}
