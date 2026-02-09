<?php

namespace App\Filament\Resources\Events\Pages;

use App\Filament\Resources\Events\EventResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

use App\Filament\Resources\EventsResource; //novo
use Illuminate\Database\Eloquent\SoftDeletes; //novo

class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;


    protected bool $persistFilters = true; //mpede que o Filament “lembre” filtros antigos Cada navegação começa limpa

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Novo Agendamento'), // Botão de criação está em português: Novo Agendamento.
        ];
    }
    protected function getTableQuery(): Builder|Relation|null
    {
        $query = static::$resource::getEloquentQuery();
        if ($status = request('status')) {
            match ($status) {
                'pendentes'  => $query->where('status', 'pending'),
                'concluidos' => $query->where('status', 'done'),
                'apagados'   => $query->onlyTrashed(),
                'todos'      => $query->withTrashed(),
                default      => null,
             };
            } else {
                // comportamento padrão: só ativos
                $query->whereNull('deleted_at');
            }
            return $query;
        }

    // Sobrescrevendo query para filtros do dashboard
    // protected function getTableQuery(): Builder|Relation|null funcionando ------
    // {
    //     $query = static::$resource::getEloquentQuery(); funcionando -----

        // Filtro opcional via dashboard
        // if ($status = request('status')) {
        //     $query->where('status', $status);
        // }
        // novo // Verifica se existe filtro via query stringstring do dashboard,  
    //    if ($status = request('status')) { funcionando ---------------
    //     $query = match($status) {
    //         'pendentes' => $query->where('status', 'pending'),
    //         'concluidos' => $query->where('status', 'done'),
    //         'apagados' => $query->onlyTrashed(),
    //         'todos' => $query->withTrashed(),
    //         default => $query,
    //     };
    // } //novo

        // return $query; --- funcionando 
    // }
    public static function getEloquentQuery(): Builder //novo .....
    {
        $query = parent::getEloquentQuery();

        // Se estiver filtrando por status 'apagados', deixa somente apagados
        if (request('status') === 'apagados') {
            $query = $query->onlyTrashed();
        } else {
            // Senão, pega todos, incluindo apagados
            $query = $query->withTrashed();
        }

        return $query;
    } //....novo
}




