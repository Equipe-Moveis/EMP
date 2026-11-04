<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    // Não declaramos $table: o Laravel deduz "categorias" a partir do nome da classe
    // (Categoria + "s"). Só precisamos declarar quando o nome real foge disso.

    // $fillable = lista branca das colunas que podem ser gravadas "em massa"
    // (Categoria::create($dados) e $categoria->update($dados)).
    // Protege contra "mass assignment": sem a lista, alguém poderia mandar um campo
    // extra pelo formulário e gravar o que não deveria.
    protected $fillable = ['descricao'];

    // Uma categoria tem MUITOS produtos. O Laravel deduz que a chave estrangeira em
    // produtos se chama categoria_id (nome do model + _id).
    // Isto substitui o INNER JOIN que você escrevia à mão no DAL.
    public function produtos(): HasMany
    {
        return $this->hasMany(Produto::class);
    }

    public function moveis(): HasMany
    {
        return $this->hasMany(Movel::class);
    }
}
