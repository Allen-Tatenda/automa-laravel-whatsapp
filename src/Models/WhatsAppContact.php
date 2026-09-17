<?php
namespace Automa\WhatsApp\Models;
use Illuminate\Database\Eloquent\Model;
class WhatsAppContact extends Model
{
    protected $table = 'whatsapp_contacts';
    protected $guarded = [];
    protected $casts = ['metadata' => 'array', 'last_seen_at' => 'datetime'];
    public function conversations(){ return $this->hasMany(WhatsAppConversation::class, 'contact_id'); }
    public function messages(){ return $this->hasMany(WhatsAppMessage::class, 'contact_id'); }
}
