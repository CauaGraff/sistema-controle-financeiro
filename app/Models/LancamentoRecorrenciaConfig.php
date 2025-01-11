<?php

namespace App\Models;

use App\Models\Empresas;
use App\Models\CategoriaContas;
use App\Models\FornecedorCliente;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LancamentoRecorrenciaConfig extends Model
{
    use HasFactory;

    protected $table = 'lancamento_recorrencias_config';

    protected $fillable = [
        'descricao',
        'tipo_recorrencia',
        'data_inicio',
        'data_fim',
        'valor',
        'id_categoria',
        'id_empresa',
        'id_fornecedor_cliente',
        'ativo',
        'tipo'
    ];

    public function categoria()
    {
        return $this->belongsTo(CategoriaContas::class, 'id_categoria');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresas::class, 'id_empresa');
    }

    public function fornecedorCliente()
    {
        return $this->belongsTo(FornecedorCliente::class, 'id_fornecedor_cliente');
    }
}
