<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    // Agendamento dos comandos
    protected function schedule(Schedule $schedule)
    {
        // Roda a cada hora e apaga eventos concluídos há mais de 24h
        $schedule->command('events:delete-old-completed')->hourly();
    }

    // Carrega os comandos artisan
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
