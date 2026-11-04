<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('moveis', function (Blueprint $table) {
            $table->id();

            $table->string('descricao', 60);
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->decimal('preco_venda', 10, 2);

            // data_cadastro continua existindo porque o usuário escolhe essa data no formulário.
            // Não dá para trocar por created_at, que é a data em que o registro foi gravado.
            $table->date('data_cadastro');

            // No PHP puro a coluna era NOT NULL e o código gravava '' quando vinha vazia
            // (trim($_POST['observacao'] ?? '')). No Laravel o natural é permitir NULL.
            $table->string('observacao', 255)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moveis');
    }
};
