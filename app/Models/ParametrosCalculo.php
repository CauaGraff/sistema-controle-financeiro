<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametrosCalculo extends Model
{
    use HasFactory;

    protected $table = 'parametros_calculo';
    protected $fillable = [
        'descricao',
        'indice',
        'P_V',
        'aplicacao'
    ];
}
