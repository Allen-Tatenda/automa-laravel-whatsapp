<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(){ Schema::create('whatsapp_contacts',function(Blueprint $t){$t->id();$t->string('wa_id')->unique();$t->string('name')->nullable();$t->string('phone')->nullable();$t->json('metadata')->nullable();$t->timestamp('last_seen_at')->nullable();$t->timestamps();}); } public function down(){Schema::dropIfExists('whatsapp_contacts');} };
