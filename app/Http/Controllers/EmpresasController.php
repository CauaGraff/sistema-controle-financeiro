<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Empresas;
use Illuminate\Http\Request;

class EmpresasController extends Controller
{
    public function index()
    {
        $empresas = Empresas::all();
        return view('admin.empresas.index', compact('empresas'));
    }

    public function create()
    {
        return view('admin.empresas.formcreate');
    }


    public function save(Request $request)
    {

        $request->validate([
            'nome' => 'required|string|max:255',
            'cnpj_cpf' => 'required|max:14',
            'cep' => 'max:8'
        ]);

        // Criação da empresa
        Empresas::create([
            'nome' => $request->nome,
            'cnpj_cpf' => $request->cnpj_cpf,
            'cep' => $request->cep,
            'cidade' => $request->cidade,
            'bairro' => $request->bairro,
            'rua' => $request->rua,
            'active' => 1
        ]);
        return redirect()->route('adm.empresas')->with('alert-success', 'Empresa Cadastrada com sucesso!');
    }

    public function delete(int $id)
    {
        if (Empresas::find($id)->delete()) {
            return redirect()
                ->back()->with('alert-success', 'Deletado com sucesso!');
        }
        return redirect()
            ->back()->with('alert-danger', 'Erro ao deletar!');
    }

    public function show($id)
    {
        $empresa = Empresas::findOrFail($id);
        $usuarios = $empresa->usuarios; // Assumindo que a relação está configurada no modelo Empresa
        $allUsers = User::whereNotIn('id', $usuarios->pluck('id'))->get();
        return view('admin.empresas.view', compact('empresa', 'usuarios', 'allUsers'));
    }
    public function addUsuario(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $empresa = Empresas::findOrFail($id);
        $empresa->usuarios()->attach($request->user_id); // Adiciona o usuário à empresa

        return redirect()->route('adm.empresas.show', $id)->with('success', 'Usuário adicionado com sucesso!');
    }
    public function removeUsuario($idEmpersa, $idUser)
    {
        $empresa = Empresas::findOrFail($idEmpersa);

        // Verificar se o usuário tem relação com a empresa
        if (!$empresa->usuarios->contains($idUser)) {
            return redirect()
                ->back()
                ->with('alert-danger', 'Este usuário não está cadastrado nesta empresa!');
        }

        // Remove o usuário da empresa
        $empresa->usuarios()->detach($idUser);

        return redirect()
            ->back()
            ->with('alert-success', 'Usuário removido com sucesso!');
    }
}
