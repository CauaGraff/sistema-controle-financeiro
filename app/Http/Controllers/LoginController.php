<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    public function index()
    {
        return view("login");
    }
    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required|min:8'
        ], [
            'email.required' => 'Preencha o e-mail',
            'password.required' => 'Preencha a senha',
            'password.min' => 'Esse campo tem que ter no mínimo :min caracteres'
        ]);

        if (Auth::attempt(['email' => $credentials["email"], 'password' => $credentials["password"], 'active' => 1])) {
            $request->session()->regenerate();
            if (Auth::user()->isAdmim()) {
                return redirect()->route('home.adm');
            } else {
                // Selecionar a primeira empresa associada ao usuário
                $primeiraEmpresa = Auth::user()->empresas()->first();

                if ($primeiraEmpresa) {
                    // Definir a primeira empresa na sessão automaticamente
                    session([
                        'empresa_id' => $primeiraEmpresa->id,
                        'empresa_nome' => $primeiraEmpresa->nome,
                    ]);
                } else {
                    // Opcional: se o usuário não tiver empresas associadas, você pode redirecionar ou tratar de forma adequada
                    return redirect()->route('empresa.selecionar')->withErrors('Nenhuma empresa disponível.');
                }

                // Redirecionar para a home
                return redirect()->route('home');
            }
        }

        // Caso o login falhe
        return back()->withErrors([
            'email' => 'As credenciais fornecidas estão incorretas ou a conta está inativa.',
        ]);
    }


    public function destroy()
    {
        Auth::logout();

        return redirect()->route('login');
    }
}
