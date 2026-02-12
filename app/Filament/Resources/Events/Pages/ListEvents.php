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
      public function mount(): void //novo 1*O mount()Ele lê a URL (status=pendentes, status=concluidos, etc.)git 
    {
        parent::mount();

        $status = request()->query('status');

        if ($status) {
            $this->tableFilters = match ($status) { //2*Traduz isso em filtros da tabela ($this->tableFilters)
                'pendentes' => [
                    'status' => ['value' => 'pending'],
                    'trashed' => ['value' => 'ativos'],
                ],
                'concluidos' => [
                    'status' => ['value' => 'done'],
                    'trashed' => ['value' => 'ativos'],
                ],
                'apagados' => [
                    'trashed' => ['value' => 'apagados'],
                ],
                'todos' => [
                    'trashed' => ['value' => 'todos'],
                ],
                default => [],
                // Tabela entende: “Ah, o usuário quer ver só os pendentes, e quero só os ativos”
// É como quando você chega na escola e a professora diz:
// “Hoje vamos brincar só com carrinhos” → todo mundo só olha para os carrinhos.
            };
        }
    }
} 