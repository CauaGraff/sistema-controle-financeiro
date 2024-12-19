<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FornecedorCliente extends Model
{
    use HasFactory;

    protected $table = 'fornecedor_cliente';

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
    public function formatarDocumento()
    {
        // Remover caracteres não numéricos
        $documento = preg_replace('/\D/', '', $this->cnpj_cpf);

        // Verificar se é CNPJ (14 dígitos) ou CPF (11 dígitos)
        if (strlen($documento) == 14) {
            // Formatar como CNPJ
            return substr($documento, 0, 2) . '.' .
                substr($documento, 2, 3) . '.' .
                substr($documento, 5, 3) . '/' .
                substr($documento, 8, 4) . '-' .
                substr($documento, 12, 2);
        } elseif (strlen($documento) == 11) {
            // Formatar como CPF
            return substr($documento, 0, 3) . '.' .
                substr($documento, 3, 3) . '.' .
                substr($documento, 6, 3) . '-' .
                substr($documento, 9, 2);
        }

        return false; // Retorna false se o documento não for válido
    }

}
