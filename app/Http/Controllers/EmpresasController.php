<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Empresas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        // Validar os dados de entrada
        $request->validate([
            'nome' => 'required|max:255',
            'cnpj_cpf' => 'required|max:18',
            'cep' => 'max:9',
            'cidade' => 'max:255',
            'bairro' => 'max:255',
            'rua' => 'string|max:255'
        ]);

        // Remover os caracteres especiais (., -, /) do CNPJ/CPF e CEP
        $cnpj_cpf = preg_replace('/[^0-9]/', '', $request->cnpj_cpf);
        $cep = preg_replace('/[^0-9]/', '', $request->cep);

        // Criar a nova empresa (empresa)
        Empresas::create([
            'nome' => $request->nome,
            'cnpj_cpf' => $cnpj_cpf,
            'cep' => $cep,
            'cidade' => $request->cidade,
            'bairro' => $request->bairro,
            'rua' => $request->rua,
            'active' => 1 // Supondo que '1' significa que a empresa está ativa
        ]);

        // Redirecionar com a mensagem de sucesso
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
        $allUsers = User::whereNotIn('id', $usuarios->pluck('id'))->where('id_typeuser', 3)->get();
        session([
            'empresa_id' => $empresa->id,
            'empresa_nome' => $empresa->nome,
        ]);

        return view('admin.empresas.view', compact('empresa', 'usuarios', 'allUsers'));
    }
    public function addUsuario(Request $request, $idEmpresa)
    {
        $empresa = Empresas::findOrFail($idEmpresa);
        $usuariosIds = explode(',', $request->usuarios);

        foreach ($usuariosIds as $usuarioId) {
            $empresa->usuarios()->attach($usuarioId);
        }

        return redirect()->back()->with('success', 'Usuários adicionados com sucesso!');
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

    public function definirEmpresa($id)
    {
        // Verificar se a empresa existe
        $empresa = Empresas::findOrFail($id);

        // Verificar se o usuário autenticado tem permissão para acessar esta empresa
        $empresaUsuario = auth()->user()->empresas()->where('empresas.id', $id)->first();

        if (!$empresaUsuario) {
            // Se o usuário não tem permissão, redirecione para uma página de erro ou home
            return redirect()->route('empresa.selecionar')->withErrors('Você não tem permissão para acessar esta empresa.');
        }

        // Se o usuário tem permissão, armazenar a empresa na sessão
        session([
            'empresa_id' => $empresa->id,
            'empresa_nome' => $empresa->nome,
        ]);

        // Opcional: redirecionar para a página principal ou onde for necessário
        return redirect()->route('home')->with('alert-success', 'Empresa definida com sucesso.');
    }

    public function edit($id)
    {
        $empresa = Empresas::findOrFail($id);
        return view('admin.empresas.formupdate', compact('empresa'));
    }
    public function update(Request $request, $id)
    {
        // Validação dos dados
        $request->validate([
            'nome' => 'required|string|max:255',
            'cnpj_cpf' => 'required|max:18',
            'cep' => 'max:9',
            'cidade' => 'required|string|max:255',
            'bairro' => 'required|string|max:255',
            'rua' => 'required|string|max:255'
        ]);

        // Encontrar a empresa no banco de dados
        $empresa = Empresas::findOrFail($id);

        // Atualizar os dados
        $empresa->update([
            'nome' => $request->nome,
            'cnpj_cpf' => preg_replace('/[^0-9]/', '', $request->cnpj_cpf),
            'cep' => preg_replace('/[^0-9]/', '', $request->cep),
            'cidade' => $request->cidade,
            'bairro' => $request->bairro,
            'rua' => $request->rua
        ]);

        // Redirecionar com mensagem de sucesso
        return redirect()->route('adm.empresas')->with('alert-success', 'Empresa atualizada com sucesso!');
    }
}
