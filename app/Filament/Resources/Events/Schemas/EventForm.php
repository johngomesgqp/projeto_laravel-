<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;



class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\Select::make('user_id')
                ->label('Usuário')
                ->relationship('user', 'name')
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\TextInput::make('title')
                ->label('Título do Evento')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('description')
                ->label('Descrição')
                ->columnSpanFull(),

            Forms\Components\DateTimePicker::make('starts_at')
                ->label('Início')
                ->required(),

            Forms\Components\DateTimePicker::make('ends_at')
                ->label('Término')
                ->required(),
        ]);
    }
}
