<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->string('status')->default('pending')->change();
            $table->id();

            $table->foreignId('user_id') //está vinculado ao usuário dono do evento.
                ->constrained()
                ->cascadeOnDelete(); //garante que, se o usuário for apagado, os eventos dele também sejam apagados.

            $table->string('title');
            $table->text('description')->nullable();

            $table->dateTime('starts_at');
            $table->dateTime('ends_at');

            $table->string('status')->default('active'); // status com "default" "active"já facilita para cancelar eventos sem deletar.

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('events', function (Blueprint $table) {
            $table->string('status')->default('active')->change();
        });
    }
};
