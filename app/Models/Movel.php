<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movel extends Model
{

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
            'data_cadastro' => 'date',
        ];
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function itens(): HasMany
    {
        return $this->hasMany(MovelItem::class);
    }

    public function produtos(): BelongsToMany
    {
        return $this->belongsToMany(Produto::class, 'movel_itens')
            ->withPivot('quantidade')
            ->withTimestamps();
    }

    public function movimentacoes(): HasMany
    {
        return $this->hasMany(Movimentacao::class);
    }
}
