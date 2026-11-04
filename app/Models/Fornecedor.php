<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fornecedor extends Model
{
    // O Laravel pluraliza em inglês e deduziria "fornecedors".
    // Por isso informamos o nome real da tabela.
    protected $table = 'fornecedores';

    protected $fillable = ['nome', 'cnpj', 'telefone', 'email', 'cidade', 'uf'];

    // Um fornecedor tem MUITOS produtos (chave estrangeira deduzida: fornecedor_id).
    public function produtos(): HasMany
    {
        return $this->hasMany(Produto::class);
    }
}
