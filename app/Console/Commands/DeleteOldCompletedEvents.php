<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use Carbon\Carbon;

class DeleteOldCompletedEvents extends Command
{
    protected $signature = 'events:delete-old-completed';
    protected $description = 'Apaga eventos concluídos com mais de 24 horas';

    public function handle()
    {
        $deleted = Event::where('status', 'done')
            ->where('updated_at', '<=', Carbon::now()->subDay()) // registros com mais de 24h
            ->delete();

        $this->info("{$deleted} eventos concluídos apagados.");
    }
}
