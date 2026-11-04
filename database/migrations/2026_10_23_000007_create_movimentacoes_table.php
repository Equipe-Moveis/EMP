<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // TABELA DE MOVIMENTO (requisito do trabalho).
        // categorias, fornecedores, produtos e móveis são CADASTROS: mudam pouco e são editados.
        // movimentacoes registra EVENTOS: cada entrada ou saída de estoque vira uma linha
        // e ela nunca é editada nem apagada. É o histórico do estoque.
        //
        // Nome da tabela: o Laravel pluralizaria "Movimentacao" para "movimentacaos".
        // No Model vamos declarar protected $table = 'movimentacoes'.
        Schema::create('movimentacoes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('produto_id')->constrained('produtos');

            // Quem fez a movimentação (coluna "Responsável" da tela de referência).
            $table->foreignId('user_id')->constrained('users');

            // Preenchido só quando a saída nasceu da composição de um móvel.
            // nullOnDelete(): se o móvel for apagado, o histórico de estoque permanece
            // (movel_id vira NULL). Movimentação é registro, não deve sumir junto.
            $table->foreignId('movel_id')->nullable()->constrained('moveis')->nullOnDelete();

            // enum limita os valores no próprio banco: não existe uma terceira opção por engano.
            $table->enum('tipo', ['entrada', 'saida']);
            $table->unsignedInteger('quantidade');

            // Foto do saldo logo depois da movimentação. produtos.estoque_atual muda a cada
            // movimento; sem esta coluna não seria possível mostrar "o estoque na época" no
            // histórico (coluna "Estoque atual" da tela de referência).
            $table->unsignedInteger('estoque_apos');

            $table->string('observacao', 255)->nullable();

            // created_at = data e hora da movimentação.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimentacoes');
    }
};
