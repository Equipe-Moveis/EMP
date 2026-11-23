<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Movel;
use App\Models\MovelItem;
use App\Models\Produto;
use Illuminate\Database\Seeder;

class MovelSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = Categoria::pluck('id', 'descricao');
        $produtos = Produto::pluck('id', 'descricao');

        $moveis = [
            [
                'descricao' => 'Armário de Cozinha Compacto',
                'categoria' => 'Cozinha',
                'preco_venda' => 1890.00,
                'data_cadastro' => '2026-10-01',
                'observacao' => 'Modelo padrão com duas portas.',
                'itens' => [
                    ['produto' => 'MDF 15mm Branco 1,83x2,75m', 'quantidade' => 2],
                    ['produto' => 'Dobradiça 35mm', 'quantidade' => 4],
                    ['produto' => 'Puxador Alumínio 160mm', 'quantidade' => 2],
                    ['produto' => 'Fita de borda branca 22mm (rolo 50m)', 'quantidade' => 1],
                ],
            ],
            [
                'descricao' => 'Guarda-Roupa Casal Carvalho',
                'categoria' => 'Quarto',
                'preco_venda' => 3290.00,
                'data_cadastro' => '2026-10-05',
                'observacao' => 'Com portas de correr e gavetas internas.',
                'itens' => [
                    ['produto' => 'MDF 18mm Carvalho 1,83x2,75m', 'quantidade' => 4],
                    ['produto' => 'MDF 6mm Branco (fundo) 1,83x2,75m', 'quantidade' => 2],
                    ['produto' => 'Corrediça 45cm telescópica', 'quantidade' => 4],
                    ['produto' => 'Dobradiça com amortecedor 165°', 'quantidade' => 6],
                    ['produto' => 'Fita de borda carvalho 22mm (rolo 50m)', 'quantidade' => 2],
                ],
            ],
            [
                'descricao' => 'Rack para Sala com Prateleira',
                'categoria' => 'Sala',
                'preco_venda' => 1450.00,
                'data_cadastro' => '2026-10-10',
                'observacao' => null,
                'itens' => [
                    ['produto' => 'MDF 15mm Preto 1,83x2,75m', 'quantidade' => 2],
                    ['produto' => 'Prateleira MDF carvalho 80x20cm', 'quantidade' => 2],
                    ['produto' => 'Puxador Inox 128mm', 'quantidade' => 2],
                    ['produto' => 'Suporte de prateleira invisível', 'quantidade' => 4],
                ],
            ],
            [
                'descricao' => 'Mesa de Escritório com Gaveteiro',
                'categoria' => 'Escritório',
                'preco_venda' => 2100.00,
                'data_cadastro' => '2026-10-12',
                'observacao' => 'Inclui gaveteiro móvel.',
                'itens' => [
                    ['produto' => 'Tampo MDF branco 120x60cm', 'quantidade' => 1],
                    ['produto' => 'MDF 15mm Branco 1,83x2,75m', 'quantidade' => 2],
                    ['produto' => 'Corrediça telescópica 35cm', 'quantidade' => 3],
                    ['produto' => 'Kit rodízios para gaveteiro (4 un.)', 'quantidade' => 1],
                    ['produto' => 'Parafuso chipboard 3,5x30mm (cento)', 'quantidade' => 1],
                ],
            ],
            [
                'descricao' => 'Gabinete de Banheiro',
                'categoria' => 'Banheiro',
                'preco_venda' => 980.00,
                'data_cadastro' => '2026-10-15',
                'observacao' => 'Resistente à umidade com compensado naval.',
                'itens' => [
                    ['produto' => 'Compensado Naval 18mm 2,20x1,60m', 'quantidade' => 1],
                    ['produto' => 'Porta MDF lacado branco 210x90', 'quantidade' => 1],
                    ['produto' => 'Dobradiça 35mm', 'quantidade' => 2],
                    ['produto' => 'Puxador Inox 128mm', 'quantidade' => 2],
                ],
            ],
        ];

        foreach ($moveis as $dados) {
            $categoriaId = $categorias[$dados['categoria']] ?? null;

            if (! $categoriaId) {
                $this->command?->warn("Seed de móvel ignorado: {$dados['descricao']} (categoria não encontrada).");

                continue;
            }

            $movel = Movel::updateOrCreate(
                ['descricao' => $dados['descricao']],
                [
                    'categoria_id' => $categoriaId,
                    'preco_venda' => $dados['preco_venda'],
                    'data_cadastro' => $dados['data_cadastro'],
                    'observacao' => $dados['observacao'],
                ],
            );

            foreach ($dados['itens'] as $item) {
                $produtoId = $produtos[$item['produto']] ?? null;

                if (! $produtoId) {
                    $this->command?->warn("Item ignorado em {$dados['descricao']}: {$item['produto']} (produto não encontrado).");

                    continue;
                }

                MovelItem::updateOrCreate(
                    [
                        'movel_id' => $movel->id,
                        'produto_id' => $produtoId,
                    ],
                    [
                        'quantidade' => $item['quantidade'],
                    ],
                );
            }
        }
    }
}
