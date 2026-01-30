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
            Stat::make(
                'Agendamentos',
                Event::where('status', 'pending')->count()
            )
                ->icon('heroicon-o-calendar')
                ->color('warning')
                ->url(EventResource::getUrl('index', [
                    'tableFilters' => [
                        'pending' => ['isActive' => true],
                    ],
                ])),

            // Pendentes
            Stat::make(
                'Pendentes',
                Event::where('status', 'pending')->count()
            )
                ->icon('heroicon-o-clock')
                ->color('warning')
                ->url(EventResource::getUrl('index', [
                    'tableFilters' => [
                        'pending' => ['isActive' => true],
                    ],
                ])),

            //Concluídos
            Stat::make(
                'Concluídos',
                Event::where('status', 'done')->count()
            )
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->url(EventResource::getUrl('index', [
                    'tableFilters' => [
                        'done' => ['isActive' => true],
                    ],
                ])),

            //  Apagados
            Stat::make(
                'Apagados',
                Event::onlyTrashed()->count()
            )
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->url(EventResource::getUrl('index', [
                    'tableFilters' => [
                        'deleted' => ['isActive' => true],
                    ],
                ])),
        ];
    }
}
