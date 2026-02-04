<?php

namespace App\Filament\Resources\Events\Pages;

use App\Filament\Resources\Events\EventResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation; 


class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;


    protected bool $persistFilters = true; //mpede que o Filament “lembre” filtros antigos Cada navegação começa limpa

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Novo Agendamento'), // Botão de criação está em português: Novo Agendamento.
        ];
    }

    // A assinatura precisa incluir o tipo de retorno correto
    protected function getTableQuery(): Builder|Relation|null
    {
        $query = static::$resource::getEloquentQuery();

        // Filtro opcional via dashboard
        if ($status = request('status')) {
            $query->where('status', $status);
        }

        return $query;
    }
}
