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
        'id_categoria',
        'id_empresa',
        'id_fornecedor_cliente'
    ];

    // Relacionamento com empresa
    public function empresa()
    {
        return $this->belongsTo(Empresas::class, 'id_empresa');
    }

    // Relacionamento categoria
    public function CategoriaContas()
    {
        return $this->belongsTo(CategoriaContas::class, 'id_plano_contas');
    }

    public function lancamentoBaixa()
    {

    }
}
