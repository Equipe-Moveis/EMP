<?php

namespace Database\Seeders;

use App\Models\Fornecedor;
use Illuminate\Database\Seeder;

class FornecedorSeeder extends Seeder
{
    public function run(): void
    {
        // Mesmos fornecedores do equipe_moveis.sql do PHP puro.
        $fornecedores = [
            ['Madeireira São Paulo', '12.345.678/0001-99', '(11) 98765-4321', 'contato@madeireirasp.com.br',    'São Paulo', 'SP'],
            ['Ferragens Brasil',     '98.765.432/0001-11', '(18) 99999-1234', 'vendas@ferragensbrasil.com.br', 'Assis',     'SP'],
            ['MDF Distribuidora',    '45.678.901/0001-22', '(11) 91234-5678', 'mdf@distribuidora.com.br',      'Campinas',  'SP'],
        ];

        foreach ($fornecedores as [$nome, $cnpj, $telefone, $email, $cidade, $uf]) {
            // 1º array = como PROCURAR (o CNPJ identifica o fornecedor);
            // 2º array = o que GRAVAR se não encontrar.
            Fornecedor::firstOrCreate(
                ['cnpj' => $cnpj],
                ['nome' => $nome, 'telefone' => $telefone, 'email' => $email, 'cidade' => $cidade, 'uf' => $uf],
            );
        }
    }
}
