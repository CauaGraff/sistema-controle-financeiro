<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Empresas;
use App\Models\ContaBanco;
use Illuminate\Http\Request;

class ContaBancoController extends Controller
{
    // Exibir todos os registros
    public function index()
    {
        $contas = ContaBanco::where('id_empresa', session('empresa_id'))->get(); // Obter todas as contas bancárias
        return view('contaBanco.index', compact('contas'));
    }

    // Formulário de cadastro de nova conta
    public function create()
    {
        return view('contaBanco.create');
    }

    // Armazenar nova conta bancária
    public function store(Request $request)
    {
        $validate = $request->validate([
            'nome' => 'required|max:255',
            'agencia' => 'nullable|max:20',
            'conta' => 'nullable|max:20',
        ]);

        ContaBanco::create(array_merge($request->all(), ['id_empresa' => session('empresa_id')]));

        return redirect()->route('contas_banco.index')->with('alert-success', 'Conta bancária cadastrada com sucesso.');
    }

    // Exibir o formulário de edição
    public function edit($id)
    {
        $conta = ContaBanco::findOrFail($id);
        return view('contaBanco.edit', compact('conta'));
    }

    // Atualizar os dados da conta bancária
    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|max:255',
            'agencia' => 'nullable|max:20',
            'conta' => 'nullable|max:20',
        ]);

        $conta = ContaBanco::findOrFail($id);
        $conta->update($request->all());

        return redirect()->route('contas_banco.index')->with('alert-success', 'Conta bancária atualizada com sucesso.');
    }

    // Excluir uma conta bancária
    public function destroy($id)
    {
        $conta = ContaBanco::findOrFail($id);
        $conta->delete();

        return redirect()->route('contas_banco.index')->with('alert-success', 'Conta bancária excluída com sucesso.');
    }
}
