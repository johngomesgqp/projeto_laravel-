<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Filament\Resources\Events\EventResource;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Agendamentos', Event::count())
                ->icon('heroicon-o-calendar')
                ->url(EventResource::getUrl('index')),

            Stat::make('Pendentes', Event::where('status', 'pending')->count())
                ->icon('heroicon-o-clock')
                ->color('warning')
                ->url(EventResource::getUrl('index') . '?status=pending'
                ),

            Stat::make('Concluídos', Event::where('status', 'done')->count())
                ->icon('heroicon-o-check-circle')
                ->color('success')
                 ->url(EventResource::getUrl('index') . '?status=done'),
                 
        ];
    }
}

