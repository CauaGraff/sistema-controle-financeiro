<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Empresas;
use Illuminate\Http\Request;

class AdmController extends Controller
{
    public function index()
    {
        $clientesAtivos = User::where('id_typeuser', 3)->where('active', 1)->count();
        $empresasAtivas = Empresas::where('active', 1)->where('active', 1)->count();
        $usuariosEscritorio = User::where('id_typeuser', 2)->where('active', 1)->count();

        return view('admin.home', compact('clientesAtivos', 'empresasAtivas', 'usuariosEscritorio'));
    }
}
