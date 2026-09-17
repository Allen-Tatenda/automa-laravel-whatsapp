<?php
namespace Automa\WhatsApp\Console;
use Illuminate\Console\Command;
class InstallCommand extends Command
{
    protected $signature='whatsapp:install {--force : Overwrite published files}';
    protected $description='Install Laravel WhatsApp automation engine';
    public function handle(): int
    {
        $this->call('vendor:publish',['--tag'=>'whatsapp-config','--force'=>$this->option('force')]);
        $this->call('vendor:publish',['--tag'=>'whatsapp-migrations','--force'=>$this->option('force')]);
        $this->info('WhatsApp engine installed. Configure WHATSAPP_* variables and run php artisan migrate.');
        return self::SUCCESS;
    }
}
