<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Fornecedor;
use App\Models\Movel;
use App\Models\Movimentacao;
use App\Models\Produto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitante_e_redirecionado_para_o_login(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_cabecalho_mostra_a_data_de_hoje_em_portugues(): void
    {
        $this->travelTo('2026-11-18 10:00:00');

        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSeeText('Quarta-feira, 18 de novembro de 2026');
    }

    public function test_cards_do_topo_consideram_apenas_produtos_ativos(): void
    {
        $ferragens = $this->criarFornecedor('Ferragens Brasil');
        $distribuidora = $this->criarFornecedor('MDF Distribuidora');

        // Normal: 50 x R$ 10 = R$ 500
        $this->criarProduto(['fornecedor_id' => $ferragens->id, 'preco_custo' => 10, 'estoque_atual' => 50, 'estoque_minimo' => 10]);
        // Zerado: entra em "abaixo do mínimo" e em "sem estoque"
        $this->criarProduto(['fornecedor_id' => $ferragens->id, 'preco_custo' => 2.5, 'estoque_atual' => 0, 'estoque_minimo' => 20]);
        // Exatamente no mínimo: o scope estoqueBaixo() usa <=, então conta. 5 x R$ 100 = R$ 500
        $this->criarProduto(['fornecedor_id' => $distribuidora->id, 'preco_custo' => 100, 'estoque_atual' => 5, 'estoque_minimo' => 5]);
        // Inativo e abaixo do mínimo: não entra em nenhum número
        $this->criarProduto(['fornecedor_id' => $distribuidora->id, 'preco_custo' => 1000, 'estoque_atual' => 3, 'estoque_minimo' => 10, 'ativo' => false]);

        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertOk()
            ->assertViewHas('produtosAtivos', 3)
            ->assertViewHas('abaixoDoMinimo', 2)
            ->assertSeeText('de 2 fornecedores')
            ->assertSeeText('1 sem estoque')
            ->assertSeeText('R$ 1.000,00');
    }

    public function test_repor_estoque_lista_do_mais_critico_para_o_menos_critico(): void
    {
        // Nomes em ordem alfabética contrária à urgência, para a ordenação não acertar por acaso.
        $this->criarProduto(['descricao' => 'Arruela', 'estoque_atual' => 8, 'estoque_minimo' => 10]);   // 80%
        $this->criarProduto(['descricao' => 'Bucha', 'estoque_atual' => 12, 'estoque_minimo' => 30]);    // 40%
        $this->criarProduto(['descricao' => 'Cola', 'estoque_atual' => 0, 'estoque_minimo' => 20]);      // 0%
        $this->criarProduto(['descricao' => 'Puxador', 'estoque_atual' => 80, 'estoque_minimo' => 15]);  // acima do mínimo
        $this->criarProduto(['descricao' => 'Porta inativa', 'estoque_atual' => 0, 'estoque_minimo' => 5, 'ativo' => false]);

        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSeeTextInOrder(['Cola', 'Bucha', 'Arruela'])
            ->assertSee('width: 40%', false)
            ->assertDontSeeText('Puxador')
            ->assertDontSeeText('Porta inativa');
    }

    public function test_repor_estoque_mostra_no_maximo_cinco_produtos(): void
    {
        foreach (['Item A' => 1, 'Item B' => 2, 'Item C' => 3, 'Item D' => 4, 'Item E' => 5, 'Item F' => 9] as $descricao => $atual) {
            $this->criarProduto(['descricao' => $descricao, 'estoque_atual' => $atual, 'estoque_minimo' => 10]);
        }

        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSeeTextInOrder(['Item A', 'Item B', 'Item C', 'Item D', 'Item E'])
            ->assertDontSeeText('Item F');
    }

    public function test_ultimos_moveis_mostra_os_tres_mais_recentes(): void
    {
        $quarto = Categoria::create(['descricao' => 'Quarto']);

        $this->criarMovel($quarto, 'Escrivaninha com gaveta', '2026-11-05', 780);
        $this->criarMovel($quarto, 'Guarda-roupa 3 portas', '2026-11-17', 2000);
        $this->criarMovel($quarto, 'Mesa antiga', '2026-10-01', 500);
        $this->criarMovel($quarto, 'Armário aéreo', '2026-11-12', 1350);

        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertOk()
            ->assertViewHas('moveisCadastrados', 4)
            ->assertSeeText('Último em 17/11/2026')
            ->assertSeeTextInOrder([
                'Guarda-roupa 3 portas', 'Quarto, 17/11/2026', 'R$ 2.000,00',
                'Armário aéreo', 'Escrivaninha com gaveta',
            ])
            ->assertDontSeeText('Mesa antiga');
    }

    public function test_ultimas_movimentacoes_mostra_as_cinco_mais_recentes(): void
    {
        $responsavel = User::factory()->create(['name' => 'Administrador']);
        $produto = $this->criarProduto(['descricao' => 'Puxador Inox 128mm']);

        $movimentos = [
            ['2026-11-15 08:00', Movimentacao::ENTRADA, 10, 50],
            ['2026-11-16 15:27', Movimentacao::ENTRADA, 30, 80],
            ['2026-11-17 09:12', Movimentacao::SAIDA, 2, 78],
            ['2026-11-17 16:40', Movimentacao::ENTRADA, 20, 98],
            ['2026-11-18 10:05', Movimentacao::SAIDA, 8, 90],
            ['2026-11-18 14:32', Movimentacao::SAIDA, 4, 86],
        ];

        foreach ($movimentos as [$quando, $tipo, $quantidade, $saldo]) {
            $this->travelTo($quando);
            Movimentacao::create([
                'produto_id' => $produto->id,
                'user_id' => $responsavel->id,
                'tipo' => $tipo,
                'quantidade' => $quantidade,
                'estoque_apos' => $saldo,
            ]);
        }

        $this->actingAs($responsavel)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSeeTextInOrder(['18/11 14:32', 'Saída', '−4', '86', 'Administrador'])
            ->assertSeeTextInOrder(['18/11 14:32', '18/11 10:05', '17/11 16:40', '17/11 09:12', '16/11 15:27'])
            ->assertSeeTextInOrder(['16/11 15:27', 'Puxador Inox 128mm', 'Entrada', '+30', '80'])
            ->assertDontSeeText('15/11 08:00');
    }

    public function test_mostra_avisos_quando_ainda_nao_ha_dados(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSeeText('Nenhum produto abaixo do mínimo.')
            ->assertSeeText('Nenhum cadastrado')
            ->assertSeeText('Nenhum móvel cadastrado ainda.')
            ->assertSeeText('Nenhuma movimentação registrada ainda.');
    }

    /**
     * @param  array<string, mixed>  $atributos
     */
    private function criarProduto(array $atributos = []): Produto
    {
        return Produto::create(array_merge([
            'descricao' => 'Produto de teste',
            'fornecedor_id' => $atributos['fornecedor_id'] ?? $this->criarFornecedor()->id,
            'categoria_id' => Categoria::firstOrCreate(['descricao' => 'Cozinha'])->id,
            'preco_custo' => 10,
            'preco_venda' => 20,
            'estoque_atual' => 10,
            'estoque_minimo' => 5,
            'ativo' => true,
        ], $atributos));
    }

    private function criarFornecedor(string $nome = 'Fornecedor de teste'): Fornecedor
    {
        return Fornecedor::firstOrCreate(['nome' => $nome], [
            'cnpj' => '00.000.000/0001-00',
            'telefone' => '(18) 3322-0000',
            'email' => 'contato@example.com',
            'cidade' => 'Assis',
            'uf' => 'SP',
        ]);
    }

    private function criarMovel(Categoria $categoria, string $descricao, string $dataCadastro, float $preco): Movel
    {
        return Movel::create([
            'descricao' => $descricao,
            'categoria_id' => $categoria->id,
            'preco_venda' => $preco,
            'data_cadastro' => $dataCadastro,
        ]);
    }
}
