<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Schema::table (e não Schema::create) = altera uma tabela que JÁ existe.
        Schema::table('users', function (Blueprint $table) {
            // Base da AUTORIZAÇÃO (requisito do trabalho): 'admin' ou 'funcionario'.
            $table->string('perfil', 20)->default('funcionario')->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('perfil');
        });
    }
};
