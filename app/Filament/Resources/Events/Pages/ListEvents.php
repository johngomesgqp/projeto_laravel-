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


    protected bool $persistFilters = true; // impede que o Filament “lembre” filtros antigos, cada navegação começa limpa

    protected function getHeaderActions(): array   // Cabeçalho: botão de criar novo evento
    {
        return [
            CreateAction::make()
                ->label('Novo Agendamento'), // Botão de criação está em português: Novo Agendamento.
        ];
    }

    protected function getTableQuery(): Builder|Relation|null
    {
        $query = static::$resource::getEloquentQuery();  // Query da tabela ajustada conforme filtro de status
        if ($status = request('status')) {
            //  match ($status)
            $query = match ($status) { // linha nova
                'pendentes'  => $query->where('status', 'pending'),
                'concluidos' => $query->where('status', 'done'),
                'apagados'   => $query->onlyTrashed(),
                'todos'      => $query->withTrashed(),
                // default      => null,
                default      => $query, // linha nova
            };
        // } else {
        //     // comportamento padrão: só ativos
        //     $query->whereNull('deleted_at');
        }
        return $query;
    }

    // Título dinâmico no topo da página (compatível com Filament v5)
    public function getHeading(): ?string
    {
        return match (request('status')) {
            'pendentes'  => 'Eventos Pendentes',
            'concluidos' => 'Eventos Concluídos',
            'apagados'   => 'Eventos Apagados',
            'todos'      => 'Todos os Eventos',
            default      => 'Eventos Ativos',
        };
    }

    // Ajuste para incluir/excluir eventos apagados
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
