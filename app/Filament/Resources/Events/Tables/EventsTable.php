<?php

namespace App\Filament\Resources\Events\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Tables\Filters\SelectFilter;



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
                    }),
                // ->searchable(), → serve para procurar palavras (ex: título , 
                //nome do usuário, descrição e eu estou utilizando botões)

                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // TextColumn::make('updated_at')
                //     ->label('Atualizado em')
                //     ->dateTime('d/m/Y H:i:s')
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // parte do codigo removida por ser excesso de informação para o usuria, 
                //removido para reduzir “poluição visual” 
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending'  => 'Pendentes',
                        'done'     => 'Concluídos',
                        'apagados' => 'Apagados', //novo
                    ])
                    ->query(function ($query, array $data) {
                        if (empty($data['value'])) {   //novo ...
                            return;
                        }

                        if ($data['value'] === 'apagados') {
                            $query->onlyTrashed();
                            return;
                        }

                        $query->where('status', $data['value']);   //....novo
                    }),
            ])

            ->recordActions([
                Action::make('done')
                    ->label('Concluir')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn($record) => $record->status !== 'done')
                    ->action(fn($record) => $record->update(['status' => 'done'])),

                Action::make('delete')
                    ->label('Apagar')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->visible(fn($record) => $record?->deleted_at === null) // só mostra se não estiver apagado
                    ->action(fn($record) => $record->delete()),

                    //novo
                Action::make('restore')
                    ->label('Restaurar')
                    ->icon('heroicon-o-arrow-path') 
                    ->color('primary')
                    ->visible(fn($record) => $record?->deleted_at !== null)
                    ->action(fn($record) => $record->restore()), //novo


                ViewAction::make(),

                EditAction::make()
                    ->visible(fn($record) => $record?->deleted_at === null && $record?->status !== 'done'),
            ])
      
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        // retirado para que o botão de apagar não apareca depois de ser apagado
                        //->visible(fn($records) => $records->every(fn($record) => $record->status !== 'done')),
                        ->visible(fn($records) => $records->every(fn($record) => $record->deleted_at === null && $record->status !== 'done')),
                    // colocado para quando o evento for apagado e buscado não mostrar botão de apagdo.
                ]),
            ]);
    }
}
