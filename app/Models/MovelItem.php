<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovelItem extends Model
{
    // "MovelItem" viraria "movel_items" (plural em inglês); a tabela real é "movel_itens".
    protected $table = 'movel_itens';

    protected $fillable = ['movel_id', 'produto_id', 'quantidade'];

    public function movel(): BelongsTo
    {
        return $this->belongsTo(Movel::class);
    }

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }
}
