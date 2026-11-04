<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fornecedores', function (Blueprint $table) {
            $table->id();

            // Tamanhos iguais aos do equipe_moveis.sql original.
            $table->string('nome', 35);
            $table->string('cnpj', 18); // opcional: ->unique() para impedir CNPJ repetido
            $table->string('telefone', 15);
            $table->string('email', 50);
            $table->string('cidade', 35);
            $table->char('uf', 2); // char = tamanho fixo; sigla do estado sempre tem 2 letras

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fornecedores');
    }
};
