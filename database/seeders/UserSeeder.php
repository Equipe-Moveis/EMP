<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // A senha vai em texto puro de propósito: o cast 'hashed' do model User
        // aplica o bcrypt sozinho na hora de gravar. (Só para desenvolvimento.)
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
