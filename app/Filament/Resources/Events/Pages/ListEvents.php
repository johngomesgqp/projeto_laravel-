<?php

namespace App\Filament\Resources\Events\Pages;

use App\Filament\Resources\Events\EventResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;


class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;

    protected bool $persistFilters = false;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Novo Agendamento'),
        ];
    }
// removido por que quem controla tudo é o EventsTable + Filter.
    // protected function getTableQuery(): Builder
    // {
    //     $query = static::$resource::getEloquentQuery();

    //     return match (request('status')) {
    //         'pendentes'  => $query->where('status', 'pending'),
    //         'concluidos' => $query->where('status', 'done'),
    //         'apagados'   => $query->onlyTrashed(),
    //         'todos'      => $query->withTrashed(),
    //         default      => $query->whereNull('deleted_at'),
    //     };
    // }
}
