<?php

namespace App\Filament\Resources\Events\Pages;

use App\Filament\Resources\Events\EventResource;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

    protected function mutateFormDataBeforeFill(array $data): array // Desativa todos os campos se o evento estiver concluído
    {
        $this->formDisabled = $this->record->status === 'done';
        return $data;
    }
      protected function getFormSchema(): array
    {
        return static::getResource()::form()
            ->getSchema()
            ->map(function ($component) {
                if (isset($this->formDisabled) && $this->formDisabled) {
                    return $component->disabled(); // desabilita o campo
                }
                return $component;
            })
            ->toArray();
        }

    
}
