<?php

namespace App\Models; // Caminho da pasta

use Illuminate\Database\Eloquent\Factories\HasFactory; // importando a ferramenta de Factory que serve para criar dados falsos automaticamente, muito usado em testes e seeders.
use Illuminate\Database\Eloquent\Model; //Ela importa a classe Model, que é o coração do Eloquent (ORM do Laravel).
use Illuminate\Database\Eloquent\Relations\BelongsTo; //Essa linha permite criar relacionamentos do tipo “pertence a”.
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User; //Aqui você está importando o model User do Laravel.Isso permite dizer: “Esse evento pertence a um usuário” ou “Quero acessar o usuário dono do evento”

class Event extends Model // Criação de uma classe chamada Event que herda tudo da classe Model
{
    use HasFactory, SoftDeletes; // Isso ativa o a importação do HasFactory dentro do model. quer dizer que “Esse model pode usar Factory para criar dados automaticamente”

    protected static function booted(): void
    {
        static::creating(function ($event) {
            if (empty($event->status)) {
                $event->status = 'pending';
            }
        });
    }

    protected $fillable = [ // serve para criar uma lista de campos permitidos para preenchimento automático existe por segurnaça para evitar o envio de daods indevidos e o laravel aceitar
        'user_id',       // ID do usuário que criou o evento
        'title',         // Título do evento
        'description',   // Descrição do evento
        'starts_at',     // Data/hora de início
        'ends_at',       // Data/hora de término
        'status',        // Status: 'active' ou 'cancelled'
    ];

    protected $casts = [ // diz ao laravel que “Esses campos são datas, trate como datas”
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function user(): BelongsTo // diz ao laravel “Um evento pertence a um usuário”
    {
        return $this->belongsTo(User::class);
    }
}
