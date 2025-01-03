<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContaBanco extends Model
{
    use HasFactory;

    protected $table = 'contas_banco';

    protected $fillable = ['nome', 'agencia', 'conta', 'id_empresa'];

    // Relacionamento com a empresa
    public function empresa()
    {
        return $this->belongsTo(Empresas::class, 'id_empresa');
    }
}