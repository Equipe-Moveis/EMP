<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // updateOrCreate = "atualiza se já existe, cria se não existe", procurando pelo e-mail.
        // Por isso o seeder pode rodar várias vezes sem duplicar usuários.
        //
        // A senha vai em texto puro de propósito: o cast 'hashed' do model User
        // aplica o bcrypt sozinho na hora de gravar. (Só para desenvolvimento.)
        //
        // ATENÇÃO: 'perfil' precisa estar no #[Fillable] do User, senão é ignorado
        // e o admin nasceria como 'funcionario'.
        User::query()->updateOrCreate(
            ['email' => 'admin@equipemoveis.com'],
            ['name' => 'Administrador', 'password' => '123456', 'perfil' => 'admin'],
        );

        User::query()->updateOrCreate(
            ['email' => 'funcionario@equipemoveis.com'],
            ['name' => 'Funcionário', 'password' => '123456', 'perfil' => 'funcionario'],
        );
    }
}
