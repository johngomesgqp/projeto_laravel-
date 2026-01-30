<?php

namespace App\Filament\Resources\Events\Pages;

use App\Filament\Resources\Events\EventResource;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewEvent extends ViewRecord
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->label('Editar') // Adicionado botão Voltar para retornar à lista.
                ->visible(fn () => $this->record->status !== 'done'),

            Action::make('voltar')
                ->label('Voltar')
                ->url($this->getResource()::getUrl('index'))
                ->color('gray'),
        ];
    }
}
