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


    // Adiciona o cast para data_venc
    protected $casts = [
        'data_venc' => 'datetime', // Garante que data_venc seja uma instância de Carbon
    ];

    // Relacionamento com empresa
    public function empresa()
    {
        return $this->belongsTo(Empresas::class, 'id_empresa');
    }

    // Relacionamento com categoria
    public function categoriaContas()
    {
        return $this->belongsTo(CategoriaContas::class, 'id_plano_contas');
    }

    // Relacionamento com baixa de lançamento
    public function lancamentoBaixa()
    {
        return $this->hasOne(LancamentoBaixa::class, 'id_lancamento');
    }


    public function lancamentoBaixaFilter()
    {
        return $this->hasMany(LancamentoBaixa::class, 'id_lancamento');
    }
    // Verifica se o lançamento foi pago
    public function isPago()
    {
        return $this->lancamentoBaixa()->exists();
    }

    // Retorna a data de pagamento, caso tenha ocorrido
    public function dataPagamento()
    {
        return $this->lancamentoBaixa ? $this->lancamentoBaixa->updated_at : null;
    }
}
