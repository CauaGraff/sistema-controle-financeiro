<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorecido extends Model
{
    use HasFactory;

    protected $table = 'favorecido';


    protected $fillable = [
        'cnpj_cpf',
        'telefone',
        'email',
        'cep',
        'uf',
        'cidade',
        'bairro',
        'rua',
        'complemento',
        'tipo',
        'id_empresa', // Você pode incluir isso se você estiver atribuindo o id da empresa também
    ];
}
