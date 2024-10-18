<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaContas extends Model
{
    use HasFactory;

    protected $table = 'categorias_de_contas';

    protected $fillable = [
        'descricao',
        'id_empresa',
        'id_categoria_pai'
    ];

    // Relacionamento com empresa
    public function empresa()
    {
        return $this->belongsTo(Empresas::class, 'id_empresa');
    }

    public function subcategorias()
    {
        return $this->hasMany(CategoriaContas::class, 'id_categoria_pai');
    }

    public function categoriaPai()
    {
        return $this->belongsTo(CategoriaContas::class, 'id_categoria_pai');
    }
}
