<?php
namespace Automa\WhatsApp\Models;
use Illuminate\Database\Eloquent\Model;
class WhatsAppMessage extends Model
{
    protected $table = 'whatsapp_messages';
    protected $guarded = [];
    protected $casts = ['payload' => 'array', 'sent_at' => 'datetime'];
    public function contact(){ return $this->belongsTo(WhatsAppContact::class, 'contact_id'); }
    public function conversation(){ return $this->belongsTo(WhatsAppConversation::class, 'conversation_id'); }
}
