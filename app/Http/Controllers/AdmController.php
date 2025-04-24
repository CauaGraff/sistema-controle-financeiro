<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Empresas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdmController extends Controller
{
    public function index()
    {
        $clientesAtivos = User::where('id_typeuser', 3)->where('active', 1)->where('id_escritorio', Auth::user()->id_escritorio)->count();
        $empresasAtivas = Empresas::where('active', 1)->where('active', 1)->where('id_escritorio', Auth::user()->id_escritorio)->count();
        $usuariosEscritorio = User::where('id_typeuser', 2)->where('active', 1)->where('id_escritorio', Auth::user()->id_escritorio)->count();

        return view('admin.home', compact('clientesAtivos', 'empresasAtivas', 'usuariosEscritorio'));
    }
}