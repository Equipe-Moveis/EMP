<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Schema::table (e não Schema::create) = altera uma tabela que JÁ existe.
        // A tabela users vem pronta na migration padrão do Laravel. Nunca editamos aquela
        // migration: criamos uma nova que acrescenta a coluna. Assim o histórico do banco
        // fica registrado passo a passo e o rollback continua funcionando.
        Schema::table('users', function (Blueprint $table) {
            // Base da AUTORIZAÇÃO (requisito do trabalho): 'admin' ou 'funcionario'.
            //   Autenticação = "quem é você?" (login)
            //   Autorização  = "o que você pode fazer?" (perfil)
            // default 'funcionario': quem for criado sem informar perfil nasce com o menor poder.
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
