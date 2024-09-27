<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'cnpj_cpf',
        'cep',
        'cidade',
        'bairro',
        'rua',
        'active'
    ];

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'users_empresas', 'id_empresas', 'id_user')->withTimestamps();
    }
}
