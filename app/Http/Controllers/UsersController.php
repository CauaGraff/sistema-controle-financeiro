<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index(string $type)
    {
        if ($type != "cliente" && $type == "escritorio") {
            $users = User::where("id_typeuser", 2)->get();
            $type = "escritorio";
        } else {
            $users = User::where("id_typeuser", 3)->get();
            $type = "cliente";
        }
        return view('admin.users.index', compact('users', 'type'));
    }
    public function formUser(string $type)
    {
        if ($type == "cliente") {
            $typeuser = 3;
        } else {
            $typeuser = 2;
        }
        return view('admin.users.formcreate', compact('typeuser'));
    }

    public function save(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|'
        ]);

        // Criação do usuário
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_typeuser' => $request->typeuser,
            'active' => 1
        ]);

        if ($request->typeuser == 3) {
            $usuario = "clientes";
        } else {
            $usuario = "escritorio";
        }

        // Redireciona para uma página ou exibe uma mensagem
        return redirect()->route('adm.usuarios', ['type' => $usuario])->with('alert-success', 'Usuário cadastrado com sucesso!');
    }

    public function edit(int $id)
    {
        $user = User::find($id);
        if ($user) {
            return view("admin.users.formupdate", compact('user'));
        }
        return redirect()
            ->back()->with('alert-danger', 'Erro!');
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);
        $user = User::findOrFail($id);
        $typeuser = $user->id_typeuser == 3 ? "cliente" : "escritorio";
        if ($request->password == "") {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'active' => $request->active
            ]);
            if ($user) {
                return redirect()->route('adm.usuarios', ['type' => $typeuser])->with('alert-success', 'Usuário Atualizado com sucesso!');
            }
        } else {

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'active' => $request->active
            ]);
            return redirect()->route('adm.usuarios', ['type' => $typeuser])->with('alert-success', 'Usuário Atualizado com sucesso!');
        }
        return redirect()->route('adm.usuarios', ['type' => $typeuser])->with('alert-danger', 'Usuário Atualizado com sucesso!');
    }

    public function delete(int $id, Request $request)
    {
        if (User::find($id)->delete()) {
            return redirect()
                ->back()->with('alert-success', 'Usuário Deletado com sucesso!');
        }
        return redirect()
            ->back()->with('alert-danger', 'Erro ao deletar!');
    }

    public function searchByName(Request $request)
    {
        $users = User::where('name', 'like', '%' . $request->name . '%')->get();

        return response()->json($users);
    }
}
