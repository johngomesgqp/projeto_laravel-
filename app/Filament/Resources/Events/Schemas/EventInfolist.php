<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EventInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('Usuário'),

                TextEntry::make('title')
                    ->label('Título do Evento'),

                TextEntry::make('description')
                    ->label('Descrição')
                    ->placeholder('-')
                    ->columnSpanFull(),

                TextEntry::make('starts_at')
                    ->label('Início')
                    ->dateTime('d/m/Y H:i:s'),

                TextEntry::make('ends_at')
                    ->label('Término')
                    ->dateTime('d/m/Y H:i:s'),

                TextEntry::make('status')
                    ->label('Status'),

                TextEntry::make('created_at')
                    ->label('Criado em:')
                    ->dateTime('d/m/Y H:i:s')
                    ->placeholder('-'),

                // TextEntry::make('updated_at')
                //     ->dateTime('d/m/Y H:i:s')
                //     ->placeholder('-'),
            ]);
    }
}
