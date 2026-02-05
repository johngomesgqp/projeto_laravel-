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
            Stat::make('Agendamentos', Event::withTrashed()->count())
            ->description('Agendamentos Realizados')
                ->icon('heroicon-o-calendar')
                ->url(EventResource::getUrl('index') . '?status=todos'), //novo utilziado para pegar os evenbtos de todos os staus

            Stat::make('Pendentes', Event::where('status', 'pending')->count())
            ->description('Agendamentos Pendentes')
                ->icon('heroicon-o-clock')
                ->color('warning')
                 ->color('warning')
                ->url(EventResource::getUrl('index') . '?status=pendentes'),

            Stat::make('Concluídos', Event::where('status', 'done')->count())
                ->description('Agendamentos concluídos')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->url(EventResource::getUrl('index') . '?status=concluidos'),

            Stat::make('Apagados', Event::onlyTrashed()->count())
                ->description('Agendamentos Apagados')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->url(EventResource::getUrl('index') . '?status=apagados'),
                
        ];
    }
}
