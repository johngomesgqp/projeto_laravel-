<?php

namespace App\Filament\Resources\Events\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Tables\Filters\Filter;
use App\Models\Event;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')
                    ->label('ID do Usuário'),

                TextColumn::make('user.name')
                    ->label('Usuário')
                    ->searchable(),

                TextColumn::make('title')
                    ->label('Compromisso')
                    ->searchable(),

                TextColumn::make('starts_at')
                    ->label('Dia/Hora')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'done' => 'success',
                        'pending' => 'warning',
                        default => 'gray',
                    })
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                // Filtro "Concluídos"

                Filter::make('pending')
                    ->label('Pendentes')
                    ->query(fn($query) => $query->where('status', 'pending'))
                    ->default(),

                Filter::make('done')
                    ->label('Concluídos')
                    ->query(fn($query) => $query->where('status', 'done')),
            ])

            ->recordActions([
                // Botão Concluir
                Action::make('done')
                    ->label('Concluir')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn($record) => $record->status !== 'done')
                    ->action(fn($record) => $record->update(['status' => 'done'])),

                // Botão de apagar manual
                Action::make('delete')
                    ->label('Apagar')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->visible(fn($record) => $record->status === 'done')
                    ->action(fn($record) => $record->delete()),

                ViewAction::make(),

                EditAction::make()
                    ->visible(fn($record) => $record->status !== 'done'),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn($records) => $records->every(fn($record) => $record->status !== 'done')),
                ]),
            ]);
    }
}
