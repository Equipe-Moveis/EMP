<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        // Mesmas categorias do equipe_moveis.sql do PHP puro.
        foreach (['Cozinha', 'Quarto', 'Sala', 'Banheiro', 'Escritório'] as $descricao) {
            // firstOrCreate = só cria se ainda não existir uma categoria com essa descrição.
            Categoria::firstOrCreate(['descricao' => $descricao]);
        }
    }
}
