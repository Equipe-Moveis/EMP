<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Fornecedor;
use App\Models\Produto;
use Illuminate\Database\Seeder;

class ProdutoSeeder extends Seeder
{
    public function run(): void
    {
        // pluck('id', 'descricao') monta um mapa [descrição => id].
        // Assim escrevemos o NOME da categoria/fornecedor na lista abaixo, em vez de
        // decorar números de id (que mudam se o banco for recriado).
        $categorias   = Categoria::pluck('id', 'descricao');
        $fornecedores = Fornecedor::pluck('id', 'nome');

        // [descrição, fornecedor, categoria, preço custo, preço venda, estoque atual, estoque mínimo]
        // Os 5 primeiros vêm do SQL original; os demais são dados de exemplo.
        // Os produtos 6, 9 e 10 ficam de propósito no limite ou abaixo do mínimo
        // (o 10 está zerado) para o alerta do dashboard ter o que mostrar.
        $produtos = [
            ['MDF 15mm Branco 1,83x2,75m',          'Madeireira São Paulo', 'Cozinha',    120.00, 180.00,  50, 10],
            ['MDF 18mm Carvalho 1,83x2,75m',        'Madeireira São Paulo', 'Quarto',      95.00, 140.00,  30,  5],
            ['Dobradiça 35mm',                      'Ferragens Brasil',     'Cozinha',      2.50,   5.00, 200, 50],
            ['Corrediça 45cm telescópica',          'Ferragens Brasil',     'Cozinha',     18.00,  30.00, 100, 20],
            ['Puxador Inox 128mm',                  'Ferragens Brasil',     'Sala',        12.00,  22.00,  80, 15],
            ['MDF 15mm Preto 1,83x2,75m',           'MDF Distribuidora',    'Escritório', 125.00, 185.00,   8, 10],
            ['MDF 6mm Branco (fundo) 1,83x2,75m',   'MDF Distribuidora',    'Quarto',      45.00,  70.00,  25,  8],
            ['Fita de borda branca 22mm (rolo 50m)', 'MDF Distribuidora',   'Cozinha',     28.00,  45.00,  40, 10],
            ['Dobradiça com amortecedor 165°',      'Ferragens Brasil',     'Quarto',       6.50,  12.00,  12, 30],
            ['Corrediça telescópica 35cm',          'Ferragens Brasil',     'Escritório',  14.00,  25.00,   0, 20],
            ['Parafuso 4,0x40mm (cento)',           'Ferragens Brasil',     'Sala',         9.00,  16.00,  60, 20],
            ['Porta MDF lacado branco 210x90',      'Madeireira São Paulo', 'Banheiro',   210.00, 320.00,   6,  5],
        ];

        foreach ($produtos as [$descricao, $fornecedor, $categoria, $custo, $venda, $atual, $minimo]) {
            Produto::firstOrCreate(
                ['descricao' => $descricao],
                [
                    'fornecedor_id'  => $fornecedores[$fornecedor],
                    'categoria_id'   => $categorias[$categoria],
                    'preco_custo'    => $custo,
                    'preco_venda'    => $venda,
                    'estoque_atual'  => $atual,
                    'estoque_minimo' => $minimo,
                    'ativo'          => true,
                ],
            );
        }
    }
}
