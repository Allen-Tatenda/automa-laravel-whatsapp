<?php
namespace Automa\WhatsApp\Services;
use Automa\WhatsApp\Events\MessageSent;
use Illuminate\Support\Facades\Event;
class MessageService
{
    public function __construct(protected WhatsAppClient $client) {}
    protected function send(string $to, array $payload){ $payload['messaging_product']='whatsapp'; $payload['to']=$to; $r=$this->client->sendMessage($payload); Event::dispatch(new MessageSent($to,$payload,$r->json())); return $r; }
    public function text(string $to,string $body,bool $previewUrl=false){ return $this->send($to,['type'=>'text','text'=>['body'=>$body,'preview_url'=>$previewUrl]]); }
    public function image(string $to,string $url,?string $caption=null){ return $this->send($to,['type'=>'image','image'=>array_filter(['link'=>$url,'caption'=>$caption],fn($v)=>$v!==null)]); }
    public function document(string $to,string $url,?string $filename=null,?string $caption=null){ return $this->send($to,['type'=>'document','document'=>array_filter(['link'=>$url,'filename'=>$filename,'caption'=>$caption],fn($v)=>$v!==null)]); }
    public function audio(string $to,string $url){ return $this->send($to,['type'=>'audio','audio'=>['link'=>$url]]); }
    public function location(string $to,float $lat,float $lng,?string $name=null,?string $address=null){ return $this->send($to,['type'=>'location','location'=>array_filter(['latitude'=>$lat,'longitude'=>$lng,'name'=>$name,'address'=>$address],fn($v)=>$v!==null)]); }
    public function buttons(string $to,string $body,array $buttons,?string $header=null,?string $footer=null){ return $this->send($to,['type'=>'interactive','interactive'=>array_filter(['type'=>'button','header'=>$header?['type'=>'text','text'=>$header]:null,'body'=>['text'=>$body],'footer'=>$footer?['text'=>$footer]:null,'action'=>['buttons'=>array_map(fn($b)=>['type'=>'reply','reply'=>['id'=>$b['id'],'title'=>$b['title']]],$buttons)]],fn($v)=>$v!==null)]); }
    public function list(string $to,string $body,array $sections,string $button='View options',?string $header=null,?string $footer=null){ return $this->send($to,['type'=>'interactive','interactive'=>array_filter(['type'=>'list','header'=>$header?['type'=>'text','text'=>$header]:null,'body'=>['text'=>$body],'footer'=>$footer?['text'=>$footer]:null,'action'=>['button'=>$button,'sections'=>$sections]],fn($v)=>$v!==null)]); }
    public function template(string $to,string $name,string $language='en_US',array $components=[]){ return $this->send($to,['type'=>'template','template'=>['name'=>$name,'language'=>['code'=>$language],'components'=>$components]]); }
    public function media(string $to,string $mediaId,string $type){ return $this->send($to,['type'=>$type,$type=>['id'=>$mediaId]]); }
}
