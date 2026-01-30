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
                    ->label('User'),
                TextEntry::make('title'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('starts_at')
                    ->dateTime(),
                TextEntry::make('ends_at')
                    ->dateTime(),
                TextEntry::make('status'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
