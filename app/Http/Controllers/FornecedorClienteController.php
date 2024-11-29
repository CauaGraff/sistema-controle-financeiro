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

        $validate = $request->validate([
            'nome' => 'required',
            'cnpj_cpf' => 'required|string|max:14',
            'telefone' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'cep' => 'nullable|string|size:8',
            'uf' => 'nullable|string|size:2',
            'cidade' => 'nullable|string|max:100',
            'bairro' => 'nullable|string|max:100',
            'rua' => 'nullable|string|max:100',
            'complemento' => 'nullable|string|max:255',
            'tipo' => 'required|in:F,C', // 'F' para Fornecedor e 'C' para Cliente
        ]);

        FornecedorCliente::create(array_merge($request->all(), ['id_empresa' => session('empresa_id')]));

        return redirect()->route('favorecidos.index')->with('alert-success', 'Favorecido criado com sucesso!');
    }

    public function edit(FornecedorCliente $favorecido)
    {
        return view('favorecidos.edit', compact('favorecido'));
    }

    public function update(Request $request, FornecedorCliente $favorecido)
    {
        $request->validate([
            'cnpj_cpf' => 'required|string|max:14',
            'telefone' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'cep' => 'required|string|size:8',
            'uf' => 'required|string|size:2',
            'cidade' => 'required|string|max:100',
            'bairro' => 'required|string|max:100',
            'rua' => 'required|string|max:100',
            'complemento' => 'nullable|string|max:255',
            'tipo' => 'required|in:F,C', // 'F' para Fornecedor e 'C' para Cliente
        ]);

        $favorecido->update(array_merge($request->all(), ['id_empresa' => session('empresa_id')]));

        return redirect()->route('favorecidos.index')->with('alert-success', 'Favorecido atualizado com sucesso!');
    }

    public function destroy(FornecedorCliente $favorecido)
    {
        $favorecido->delete();
        return redirect()->route('favorecidos.index')->with('alert-success', 'Favorecido excluído com sucesso!');
    }
}
