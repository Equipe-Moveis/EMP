<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // up() = o que acontece no "php artisan migrate"
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            // id() já cria a coluna id como chave primária com auto incremento.
            // No PHP puro eram 2 comandos separados no .sql (PRIMARY KEY + AUTO_INCREMENT).
            $table->id();

            $table->string('descricao', 60);

            // created_at e updated_at: o Laravel preenche sozinho a cada insert/update.
            $table->timestamps();
        });
    }

    // down() = o que acontece no "php artisan migrate:rollback".
    // Sempre desfaz o que o up() fez; é isso que permite voltar atrás sem mexer no banco na mão.
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
