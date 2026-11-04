<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // A ORDEM importa: produtos dependem de categorias e fornecedores
        // (chaves estrangeiras), então esses dois têm que existir antes.
        $this->call([
            UserSeeder::class,
            CategoriaSeeder::class,
            FornecedorSeeder::class,
            ProdutoSeeder::class,
        ]);
    }
}
