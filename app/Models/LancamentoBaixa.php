<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LancamentoBaixa extends Model
{
    use HasFactory;

    protected $table = 'lancamentos_baixa';

    protected $fillable = [
        'id_lancamento',
        'id_contaBancaria',
        'valor',
        'juros',
        'multa',
        'desconto',
        'doc',
        'anexo',
        'data_baixa'
    ];
    public function contaBancaria()
    {
        return $this->belongsTo(ContaBanco::class, 'id_contaBancaria');
    }
}
