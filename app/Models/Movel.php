<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movel extends Model
{
    // "Movel" + "s" daria "movels"; a tabela real é "moveis".
    protected $table = 'moveis';

    protected $fillable = [
        'descricao',
        'categoria_id',
        'preco_venda',
        'data_cadastro',
        'observacao',
    ];

    protected function casts(): array
    {
        return [
            'preco_venda'   => 'decimal:2',
            // 'date' transforma o texto do banco em objeto Carbon:
            // $movel->data_cadastro->format('d/m/Y') funciona direto na view.
            'data_cadastro' => 'date',
        ];
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    // As linhas da composição (movel_itens). Chave estrangeira deduzida: movel_id.
    // Era o DAL\Movel::SelectItens().
    public function itens(): HasMany
    {
        return $this->hasMany(MovelItem::class);
    }

    // Atalho para ler "quais produtos este móvel usa" sem passar pelo model MovelItem.
    // $movel->produtos->first()->pivot->quantidade traz a quantidade usada.
    public function produtos(): BelongsToMany
    {
        return $this->belongsToMany(Produto::class, 'movel_itens')
            ->withPivot('quantidade')
            ->withTimestamps();
    }

    // Saídas de estoque geradas quando este móvel foi cadastrado.
    public function movimentacoes(): HasMany
    {
        return $this->hasMany(Movimentacao::class);
    }
}
