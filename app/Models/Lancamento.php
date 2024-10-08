<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lancamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'descricao',
        'valor',
        'tipo',
        'data_venc',
        'anexo',
        'id_plano_contas',
        'id_empresa',
    ];

    // Relacionamento com empresa
    public function empresa()
    {
        return $this->belongsTo(Empresas::class, 'id_empresa');
    }

    // Relacionamento com o plano de contas
    public function planoDeContas()
    {
        // return $this->belongsTo(PlanoDeContas::class, 'id_plano_contas');
    }
}
