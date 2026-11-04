<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Movimentacao extends Model
{
    // Constantes evitam "strings mágicas" espalhadas pelo código.
    // Em vez de escrever 'entrada' em vários lugares (e errar uma letra),
    // usamos Movimentacao::ENTRADA.
    public const ENTRADA = 'entrada';
    public const SAIDA   = 'saida';

    // "Movimentacao" viraria "movimentacaos"; a tabela real é "movimentacoes".
    protected $table = 'movimentacoes';

    protected $fillable = [
        'produto_id',
        'user_id',
        'movel_id',
        'tipo',
        'quantidade',
        'estoque_apos',
        'observacao',
    ];

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }

    // Responsável pela movimentação (coluna "Responsável" da tela de referência).
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Só existe quando a saída nasceu da composição de um móvel.
    public function movel(): BelongsTo
    {
        return $this->belongsTo(Movel::class);
    }
}
