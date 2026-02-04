<?php

namespace App\Filament\Resources\Events\Pages;

use App\Filament\Resources\Events\EventResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;

    
    protected bool $persistFilters = false; //mpede que o Filament “lembre” filtros antigos Cada navegação começa limpa

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Novo Agendamento'), // Botão de criação está em português: Novo Agendamento.
        ];
    }

  
    protected function getDefaultTableFilters(): array
    {
        return [
            'pending' => [
                'isActive' => true,
            ],
        ];
    }
}