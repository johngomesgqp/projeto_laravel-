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
use App\Filament\Resources\Events\EventResource; //novo


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
            // ->filters([
            //     SelectFilter::make('status')
            //         ->label('Status')
            //         ->options([
            //             'pending'  => 'Pendentes',
            //             'done'     => 'Concluídos',
            //             'apagados' => 'Apagados', //novo
            //         ])
            //         ->query(function ($query, array $data) {
            //             if (empty($data['value'])) {   //novo ...
            //                 return;
            //             }

            //             if ($data['value'] === 'apagados') {
            //                 $query->onlyTrashed();
            //                 return;
            //             }

            //             $query->where('status', $data['value']);   //....novo
            //         }),
            // ])
            ->filters([ //novo // os filtros passam a ficar separados, Por que ?

                \Filament\Tables\Filters\SelectFilter::make('status') // status → pendentes ou concluídos
                    ->label('Status')
                    ->options([
                        'pending' => 'Pendentes',
                        'done'    => 'Concluídos',
                    ]),

                \Filament\Tables\Filters\SelectFilter::make('trashed') // trashed → ativos, apagados, todos
                    ->label('Registros')
                    ->options([
                        'ativos'   => 'Ativos',
                        'apagados' => 'Apagados',
                        'todos'    => 'Todos',
                    ])
                    ->query(function ($query, array $data) {// Dashboard funciona sem confundir os filtros
                        if (empty($data['value'])) {
                            return;
                        }

                        return match ($data['value']) { // Restore volta para pendente sem travar
                            'apagados' => $query->onlyTrashed(),
                            'todos'    => $query->withTrashed(),
                            default    => $query->whereNull('deleted_at'),
                            // Livewire não mantém filtros antigos em memória
// É como ter duas caixas de brinquedo, cada uma cuidando de uma coisa, em vez de misturar tudo na mesma caixa.
                        };
                    }),
            ]) //novo
            
            ->recordActions([

                Action::make('done')
                    ->label('Concluir')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn($record) => $record->status !== 'done')
                    //->action(fn($record) => $record->update(['status' => 'done'])), 
                    ->action(function ($record) { //novo redireciona para a seção correta após a ação. Concluir → ir para Concluídos após ação do botão
                        $record->update(['status' => 'done']);
                        return redirect(
                            EventResource::getUrl('index', ['status' => 'concluidos'])
                        );
                    }), //novo

                Action::make('delete')
                    ->label('Apagar')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->visible(fn($record) => $record?->deleted_at === null) // só mostra se não estiver apagado
                    // ->action(fn($record) => $record->delete()),
                    ->action(function ($record) {  //novo redireciona para a seção correta após a ação.  Apagar → ir para Apagados após ação do botão
                        $record->delete();
                        return redirect(
                            EventResource::getUrl('index', ['status' => 'apagados'])
                        );
                    }),

                Action::make('restore') //novo
                    ->label('Restaurar')
                    ->icon('heroicon-o-arrow-path')
                    ->color('primary')
                    ->visible(fn($record) => $record?->deleted_at !== null)
                    //->action(function ($record) { //novo redireciona para a seção correta após a ação. Restaurar → ir para Pendentes após ação do botão
                    //$record->restore();
                    //return redirect(
                    //EventResource::getUrl('index', ['status' => 'pendentes'])
                    //);
                    //}), // não mudava o status. Quando tentávamos integrar com getTableQuery() ou filtros da URL, 
                    // ele voltava “concluído” porque o filtro anterior ainda estava ativo em memória.
                    ->action(function ($record) { //novo

                        $record->restore();

                        $record->update([ //Atualizar o status para pending
                            'status' => 'pending',
                        ]);

                        return redirect( //Redirecionar para Dashboard (URL: status=pendentes)
                            \App\Filament\Resources\Events\EventResource::getUrl('index', [
                                'status' => 'pendentes',
                            ])
                        );
                    }), //novo Assim, quando a página recarrega, ela lê a URL e ajusta o filtro da 
                    //tabela para mostrar pendentes, sem confusão de estado.

                ViewAction::make('View')
                    ->label('Ver Evento'),

                EditAction::make('Edit')
                    ->label('Editar')
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
