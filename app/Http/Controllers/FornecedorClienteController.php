<?php

namespace App\Http\Controllers;

use App\Models\Favorecido;
use App\Models\FornecedorCliente;
use Illuminate\Http\Request;

class FornecedorClienteController extends Controller
{
    public function index()
    {
        // Listar todos os favorecidos da empresa da sessão
        $favorecidos = FornecedorCliente::where('id_empresa', session('empresa_id'))->get();
        return view('favorecidos.index', compact('favorecidos'));
    }

    public function create()
    {
        return view('favorecidos.create');
    }

    public function store(Request $request)
    {
        // Validação dos dados recebidos
        $validate = $request->validate([
            'nome' => 'required',
            'cnpj_cpf' => 'required|max:18',
            'telefone' => 'nullable|max:15',
            'email' => 'nullable|email|max:255',
            'cep' => 'nullable|string|size:9',
            'uf' => 'nullable|string|size:2',
            'cidade' => 'nullable|string|max:100',
            'bairro' => 'nullable|string|max:100',
            'rua' => 'nullable|string|max:100',
            'complemento' => 'nullable|string|max:255',
            'tipo' => 'required|in:F,C', // 'F' para Fornecedor e 'C' para Cliente
        ]);

        // Remover caracteres especiais do campo cnpj_cpf, telefone e cep
        $cnpj_cpf = preg_replace('/\D/', '', $request->cnpj_cpf); // Remove qualquer coisa que não seja número
        $telefone = preg_replace('/\D/', '', $request->telefone); // Remove qualquer coisa que não seja número
        $cep = preg_replace('/\D/', '', $request->cep); // Remove qualquer coisa que não seja número

        // Criar o registro no banco de dados com o id_empresa da sessão
        FornecedorCliente::create(array_merge(
            $request->all(),
            [
                'cnpj_cpf' => $cnpj_cpf,
                'telefone' => $telefone,
                'cep' => $cep,
                'id_empresa' => session('empresa_id')
            ]
        ));

        // Redirecionar com uma mensagem de sucesso
        return redirect()->route('favorecidos.index')->with('alert-success', 'Favorecido criado com sucesso!');
    }

    public function edit(FornecedorCliente $favorecido)
    {
        return view('favorecidos.edit', compact('favorecido'));
    }

    public function update(Request $request, $id)
    {
        // Validação dos dados recebidos
        $validate = $request->validate([
            'nome' => 'required',
            'cnpj_cpf' => 'required|string|max:18',
            'telefone' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'cep' => 'nullable|string|size:9',
            'uf' => 'nullable|string|size:2',
            'cidade' => 'nullable|string|max:100',
            'bairro' => 'nullable|string|max:100',
            'rua' => 'nullable|string|max:100',
            'complemento' => 'nullable|string|max:255',
            'tipo' => 'required|in:F,C', // 'F' para Fornecedor e 'C' para Cliente
        ]);

        // Buscar o fornecedor/cliente pelo ID
        $favorecido = FornecedorCliente::findOrFail($id);

        // Remover caracteres especiais do campo cnpj_cpf, telefone e cep
        $cnpj_cpf = preg_replace('/\D/', '', $request->cnpj_cpf); // Remove qualquer coisa que não seja número
        $telefone = preg_replace('/\D/', '', $request->telefone); // Remove qualquer coisa que não seja número
        $cep = preg_replace('/\D/', '', $request->cep); // Remove qualquer coisa que não seja número

        // Atualizar o fornecedor/cliente no banco de dados
        $favorecido->update(array_merge(
            $request->all(),
            [
                'cnpj_cpf' => $cnpj_cpf,
                'telefone' => $telefone,
                'cep' => $cep,
            ]
        ));

        // Redirecionar com uma mensagem de sucesso
        return redirect()->route('favorecidos.index')->with('alert-success', 'Favorecido atualizado com sucesso!');
    }

    public function destroy(FornecedorCliente $favorecido)
    {
        $favorecido->delete();
        return redirect()->route('favorecidos.index')->with('alert-success', 'Favorecido excluído com sucesso!');
    }
}
