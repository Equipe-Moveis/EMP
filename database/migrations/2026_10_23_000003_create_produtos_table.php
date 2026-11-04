<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();

            $table->string('descricao', 60);

            // CONVENÇÃO: no PHP puro era id_fornecedor; no Laravel o padrão é fornecedor_id.
            // Seguindo o padrão, o relacionamento belongsTo(Fornecedor::class) descobre a
            // coluna sozinho, sem precisar configurar nada no Model.
            //
            // constrained('fornecedores'): o Laravel tentaria adivinhar a tabela como
            // "fornecedors" (ele pluraliza em inglês), por isso informamos o nome real.
            // Sem cascade: igual ao seu SQL original (ON DELETE NO ACTION): não deixa apagar
            // um fornecedor que ainda tem produtos.
            $table->foreignId('fornecedor_id')->constrained('fornecedores');
            $table->foreignId('categoria_id')->constrained('categorias');

            // decimal(10,2) = até 99.999.999,99; use decimal (nunca float) para dinheiro.
            $table->decimal('preco_custo', 10, 2);
            $table->decimal('preco_venda', 10, 2);

            // unsigned = sem sinal, ou seja, o banco recusa valores negativos.
            // É uma segunda camada de proteção para o estoque nunca ficar abaixo de zero:
            // se algum código tentar descontar mais do que existe, o MySQL (modo estrito,
            // que é o padrão do Laravel) devolve erro em vez de gravar um número negativo.
            // Continuamos validando no código para mostrar uma mensagem amigável ao usuário.
            $table->unsignedInteger('estoque_atual')->default(0);
            $table->unsignedInteger('estoque_minimo')->default(0);

            // Exclusão lógica, igual ao PHP puro (1 = ativo, 0 = inativo).
            $table->boolean('ativo')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
