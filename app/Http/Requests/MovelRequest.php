<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

// Validação do cadastro e da edição de móveis.
// O formulário manda os dados do móvel + uma LISTA de materiais (itens):
//   itens[0][produto_id] = 3   itens[0][quantidade] = 10
//   itens[1][produto_id] = 5   itens[1][quantidade] = 4
// No PHP puro eram os arrays id_produto[] e quantidade[] separados.
class MovelRequest extends FormRequest
{
    // Quem chega aqui já passou pelo middleware 'auth' da rota (está logado).
    // Admin e funcionário podem cadastrar móveis, então é true.
    public function authorize(): bool
    {
        return true;
    }

    // Antes de validar: joga fora as linhas de material que vieram vazias
    // (produto não escolhido e quantidade em branco). Assim, na edição, a pessoa
    // pode deixar a linha em branco quando não quer adicionar nada.
    protected function prepareForValidation(): void
    {
        $itens = collect($this->input('itens', []))
            ->filter(fn ($item) => ! empty($item['produto_id']) || ! empty($item['quantidade']))
            ->values()
            ->all();

        $this->merge(['itens' => $itens]);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Na edição a rota tem o parâmetro {movel}; no cadastro, não (null).
        $editando = $this->route('movel') !== null;

        return [
            'descricao'     => ['required', 'string', 'min:3', 'max:60'],
            'categoria_id'  => ['required', 'exists:categorias,id'],
            'preco_venda'   => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'data_cadastro' => ['required', 'date', 'before_or_equal:today'],
            'observacao'    => ['nullable', 'string', 'max:255'],

            // Cadastro: precisa de pelo menos 1 material (era o 'sem_itens' do DAL).
            // Edição: adicionar materiais é opcional.
            'itens' => $editando ? ['nullable', 'array'] : ['required', 'array', 'min:1'],

            // itens.* = "cada linha da lista".
            // distinct: o mesmo produto não pode aparecer em duas linhas.
            // exists com where: só aceita produto que existe E está ativo.
            'itens.*.produto_id' => [
                'required',
                'distinct',
                Rule::exists('produtos', 'id')->where('ativo', true),
            ],
            'itens.*.quantidade' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'descricao.required'             => 'Informe a descrição do móvel.',
            'descricao.min'                  => 'A descrição deve ter pelo menos 3 letras.',
            'descricao.max'                  => 'A descrição pode ter no máximo 60 caracteres.',
            'categoria_id.required'          => 'Escolha a categoria.',
            'categoria_id.exists'            => 'Categoria inválida.',
            'preco_venda.required'           => 'Informe o preço de venda.',
            'preco_venda.numeric'            => 'O preço de venda deve ser um número.',
            'preco_venda.min'                => 'O preço de venda não pode ser negativo.',
            'data_cadastro.required'         => 'Informe a data.',
            'data_cadastro.date'             => 'Data inválida.',
            'data_cadastro.before_or_equal'  => 'A data não pode ser no futuro.',
            'observacao.max'                 => 'A observação pode ter no máximo 255 caracteres.',
            'itens.required'                 => 'Adicione pelo menos um material na composição.',
            'itens.min'                      => 'Adicione pelo menos um material na composição.',
            'itens.*.produto_id.required'    => 'Escolha o produto em todas as linhas.',
            'itens.*.produto_id.distinct'    => 'O mesmo produto foi escolhido em mais de uma linha.',
            'itens.*.produto_id.exists'      => 'Produto inválido ou desativado.',
            'itens.*.quantidade.required'    => 'Informe a quantidade em todas as linhas.',
            'itens.*.quantidade.integer'     => 'A quantidade deve ser um número inteiro.',
            'itens.*.quantidade.min'         => 'A quantidade deve ser pelo menos 1.',
        ];
    }
}