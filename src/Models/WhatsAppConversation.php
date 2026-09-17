<?php
namespace Automa\WhatsApp\Models;
use Illuminate\Database\Eloquent\Model;
class WhatsAppConversation extends Model
{
    protected $table = 'whatsapp_conversations';
    protected $guarded = [];
    protected $casts = ['state' => 'array', 'context' => 'array', 'last_message_at' => 'datetime', 'closed_at' => 'datetime'];
    public function contact(){ return $this->belongsTo(WhatsAppContact::class, 'contact_id'); }
    public function messages(){ return $this->hasMany(WhatsAppMessage::class, 'conversation_id'); }
}
