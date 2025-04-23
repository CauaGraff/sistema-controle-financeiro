<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Escritorio extends Model
{
    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'cnpj',
        'cep',
        'rua',
        'bairro',
        'cidade',
        'uf',
        'complemento',
        'obs',
        'active',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Mutator para salvar o CNPJ sem formatação.
     *
     * @param string $value
     */
    public function setCnpjAttribute($value)
    {
        $this->attributes['cnpj'] = preg_replace('/\D/', '', $value); // Remove todos os caracteres não numéricos
    }

    /**
     * Accessor para exibir o CNPJ formatado.
     *
     * @return string|null
     */
    public function getCnpjAttribute($value)
    {
        if (!$value) {
            return null;
        }

        return preg_replace(
            '/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/',
            '$1.$2.$3/$4-$5',
            $value
        ); // Formata o CNPJ como 00.000.000/0000-00
    }
}
