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
        // Validação das credenciais
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required|min:8',
        ], [
            'email.required' => 'Preencha o e-mail',
            'password.required' => 'Preencha a senha',
            'password.min' => 'Esse campo tem que ter no mínimo :min caracteres',
        ]);

        // Verifica se o campo 'remember' foi marcado
        $remember = $request->has('remember');

        // Tenta autenticar o usuário com o parâmetro 'remember'
        if (Auth::attempt(['email' => $credentials["email"], 'password' => $credentials["password"], 'active' => 1], $remember)) {
            $request->session()->regenerate();

            // Redireciona de acordo com o tipo de usuário (admin ou usuário comum)
            if (Auth::user()->isAdmim() || Auth::user()->isEscritorio()) {
                return redirect()->route('home.adm');
            } else {
                // Selecionar a primeira empresa associada ao usuário
                $primeiraEmpresa = Auth::user()->empresas()->first();

                if ($primeiraEmpresa) {
                    // Define a primeira empresa na sessão automaticamente
                    session([
                        'empresa_id' => $primeiraEmpresa->id,
                        'empresa_nome' => $primeiraEmpresa->nome,
                    ]);
                } else {
                    // Opcional: se o usuário não tiver empresas associadas
                    return redirect()->route('empresa.selecionar')->withErrors('Nenhuma empresa disponível.');
                }

                // Redireciona para a home
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
