<?php
namespace Automa\WhatsApp\Services;
class FlowEngine
{
    public function __construct(protected MessageService $messages, protected ConversationManager $conversations) {}
    public function run(array $flow,array $message,$conversation): void
    {
        foreach ($flow['nodes'] ?? [] as $node) {
            if (!$this->matches($node,$message,$conversation)) continue;
            $this->execute($node,$message,$conversation);
            if (($node['stop'] ?? false) === true) break;
        }
    }
    protected function matches(array $node,array $message,$conversation): bool
    {
        if (!isset($node['when'])) return true;
        $when=$node['when']; $actual=data_get($message,$when['field']); $expected=$when['value'];
        return match($when['operator'] ?? 'equals') { 'equals'=>strtolower((string)$actual)===strtolower((string)$expected),'contains'=>str_contains(strtolower((string)$actual),strtolower((string)$expected)),'exists'=>data_get($message,$when['field'])!==null,default=>false };
    }
    protected function execute(array $node,array $message,$conversation): void
    {
        $to=$conversation->contact->wa_id;
        switch($node['type'] ?? '') {
            case 'reply': $this->messages->text($to,$this->render($node['message'] ?? '',$conversation)); break;
            case 'buttons': $this->messages->buttons($to,$this->render($node['body'] ?? '',$conversation),$node['buttons'] ?? [],$node['header'] ?? null,$node['footer'] ?? null); break;
            case 'list': $this->messages->list($to,$this->render($node['body'] ?? '',$conversation),$node['sections'] ?? [],$node['button'] ?? 'View options',$node['header'] ?? null,$node['footer'] ?? null); break;
            case 'set_state': $this->conversations->put($conversation,$node['key'],$node['value']); break;
            case 'set_context': $this->conversations->putContext($conversation,$node['key'],$node['value']); break;
            case 'template': $this->messages->template($to,$node['name'],$node['language'] ?? 'en_US',$node['components'] ?? []); break;
        }
    }
    protected function render(string $value,$conversation): string { return preg_replace_callback('/\{\{\s*state\.([^}]+)\s*\}\}/',fn($m)=>(string)$this->conversations->state($conversation,trim($m[1]),''),$value); }
}
