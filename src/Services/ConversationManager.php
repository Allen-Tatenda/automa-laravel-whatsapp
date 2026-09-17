<?php
namespace Automa\WhatsApp\Services;
use Automa\WhatsApp\Events\ConversationStarted;
use Automa\WhatsApp\Models\WhatsAppContact;
use Automa\WhatsApp\Models\WhatsAppConversation;
use Automa\WhatsApp\Models\WhatsAppMessage;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Arr;
class ConversationManager
{
    public function start(string $waId, array $profile=[]): WhatsAppConversation
    {
        $contact=WhatsAppContact::firstOrCreate(['wa_id'=>$waId],['name'=>$profile['name'] ?? null,'metadata'=>$profile]);
        if ($profile) $contact->update(['name'=>$profile['name'] ?? $contact->name,'metadata'=>array_merge($contact->metadata ?? [],$profile),'last_seen_at'=>now()]);
        $conversation=WhatsAppConversation::firstOrCreate(['contact_id'=>$contact->id,'status'=>'open'],['state'=>[],'context'=>[],'last_message_at'=>now()]);
        if ($conversation->wasRecentlyCreated) Event::dispatch(new ConversationStarted($conversation));
        return $conversation->fresh('contact');
    }
    public function recordIncoming(WhatsAppConversation $conversation,array $message): WhatsAppMessage
    {
        $text=$message['text']['body'] ?? $message['interactive']['button_reply']['title'] ?? $message['interactive']['list_reply']['title'] ?? null;
        $model=WhatsAppMessage::firstOrCreate(['wamid'=>$message['id'] ?? null],['contact_id'=>$conversation->contact_id,'conversation_id'=>$conversation->id,'direction'=>'inbound','type'=>$message['type'] ?? 'unknown','text'=>$text,'payload'=>$message,'status'=>'received','sent_at'=>now()]);
        $conversation->update(['last_message_at'=>now()]); return $model;
    }
    public function state(WhatsAppConversation $conversation,string $key,mixed $default=null): mixed { return data_get($conversation->state ?? [],$key,$default); }
    public function put(WhatsAppConversation $conversation,string $key,mixed $value): WhatsAppConversation { $state=$conversation->state ?? []; data_set($state,$key,$value); $conversation->update(['state'=>$state]); return $conversation->fresh(); }
    public function forget(WhatsAppConversation $conversation,string $key): WhatsAppConversation { $state=$conversation->state ?? []; Arr::forget($state,$key); $conversation->update(['state'=>$state]); return $conversation->fresh(); }
    public function context(WhatsAppConversation $conversation,string $key,mixed $default=null): mixed { return data_get($conversation->context ?? [],$key,$default); }
    public function putContext(WhatsAppConversation $conversation,string $key,mixed $value): WhatsAppConversation { $ctx=$conversation->context ?? []; data_set($ctx,$key,$value); $conversation->update(['context'=>$ctx]); return $conversation->fresh(); }
    public function close(WhatsAppConversation $conversation): void { $conversation->update(['status'=>'closed','closed_at'=>now()]); }
}
