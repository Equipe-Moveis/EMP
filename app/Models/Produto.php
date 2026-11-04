<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produto extends Model
{
    // Produto -> "produtos": o Laravel acerta sozinho.

    protected $fillable = [
        'descricao',
        'fornecedor_id',
        'categoria_id',
        'preco_custo',
        'preco_venda',
        'estoque_atual',
        'estoque_minimo',
        'ativo',
    ];

    // Casts convertem o valor ao ler do banco. Sem o cast, 'ativo' chegaria como 1 ou 0;
    // com ele, chega como true ou false.
    protected function casts(): array
    {
        return [
            'preco_custo' => 'decimal:2',
            'preco_venda' => 'decimal:2',
            'ativo'       => 'boolean',
        ];
    }

    // ---------- Relacionamentos ----------

    // belongsTo = "pertence a". A chave estrangeira (fornecedor_id) fica NESTA tabela.
    // Era o INNER JOIN fornecedor do seu DAL\Produto::SelectAll().
    public function fornecedor(): BelongsTo
    {
        return $this->belongsTo(Fornecedor::class);
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function movelItens(): HasMany
    {
        return $this->hasMany(MovelItem::class);
    }

    // Todos os movimentos de estoque deste produto (entradas e saídas).
    public function movimentacoes(): HasMany
    {
        return $this->hasMany(Movimentacao::class);
    }

    // Muitos-para-muitos: em quais móveis este produto é usado.
    // 'movel_itens' é a tabela de ligação; withPivot traz a quantidade junto.
    public function moveis(): BelongsToMany
    {
        return $this->belongsToMany(Movel::class, 'movel_itens')
            ->withPivot('quantidade')
            ->withTimestamps();
    }

    // ---------- Scopes ----------
    // Scope = consulta reaproveitável. Uso: Produto::ativos()->get()
    // (o prefixo "scope" some na hora de chamar, e a primeira letra vira minúscula).

    // Substitui o "WHERE p.ativo = 1" que ficava repetido no DAL.
    public function scopeAtivos(Builder $query): Builder
    {
        return $query->where('ativo', true);
    }

    // Produtos no limite ou abaixo do mínimo (alerta do dashboard).
    // whereColumn compara DUAS colunas; o where comum compara coluna com valor.
    public function scopeEstoqueBaixo(Builder $query): Builder
    {
        return $query->whereColumn('estoque_atual', '<=', 'estoque_minimo');
    }
}
