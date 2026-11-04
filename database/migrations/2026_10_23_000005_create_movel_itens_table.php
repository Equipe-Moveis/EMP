<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabela de ligação: um móvel usa vários produtos, e um produto entra em vários móveis.
        Schema::create('movel_itens', function (Blueprint $table) {
            $table->id();

            // cascadeOnDelete(): ao apagar um móvel, o BANCO apaga os itens dele sozinho.
            // No PHP puro, o Delete() do DAL\Movel tinha que rodar um DELETE em movel_item
            // antes, só para o banco aceitar apagar o móvel. Aqui isso deixa de ser código.
            $table->foreignId('movel_id')->constrained('moveis')->cascadeOnDelete();

            // Sem cascade: não deixa apagar um produto que já foi usado em algum móvel.
            $table->foreignId('produto_id')->constrained('produtos');

            $table->unsignedInteger('quantidade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movel_itens');
    }
};
