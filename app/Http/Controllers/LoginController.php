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
    public function authenticate(Request $request): RedirectResponse
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
            return redirect()->intended('home');
        }
    }

    public function destroy()
    {
        Auth::logout();

        return redirect()->route('login');
    }
}
