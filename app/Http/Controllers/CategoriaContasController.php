<?php

namespace App\Http\Controllers;

use App\Models\CategoriaContas;
use App\Models\Empresas;
use Illuminate\Http\Request;

class CategoriaContasController extends Controller
{
    // Listar todas as categorias
    public function index()
    {
        $categorias = CategoriaContas::whereNull('id_categoria_pai')->with('subcategorias')->where("id_empresa", "=", session("empresa_id"))->get();
        return view('categorias.index', compact('categorias'));
    }

    // Mostrar formulário de criação
    public function create()
    {
        $categorias = CategoriaContas::whereNull('id_categoria_pai')->get(); // Categorias pai
        return view('categorias.create', compact('categorias'));
    }

    // Salvar nova categoria
    public function store(Request $request)
    {
        $request->validate([
            'descricao' => 'required|string|max:255',
            'id_categoria_pai' => 'nullable|exists:categorias_de_contas,id',
        ]);

        CategoriaContas::create([
            'descricao' => $request->descricao,
            'id_empresa' => session('empresa_id'),
            'id_categoria_pai' => $request->id_categoria_pai
        ]);
        return redirect()->route('categorias.index')->with('alert-success', 'Categoria cadastrada com sucesso!');
    }

    // Mostrar formulário de edição
    public function edit(CategoriaContas $categoria)
    {
        $empresas = Empresas::all();
        $categorias = CategoriaContas::whereNull('id_categoria_pai')->get(); // Categorias pai
        return view('categorias.edit', compact('categoria', 'empresas', 'categorias'));
    }

    // Atualizar categoria
    public function update(Request $request, CategoriaContas $categoria)
    {
        $request->validate([
            'descricao' => 'required|string|max:255',
            'id_categoria_pai' => 'nullable|exists:categorias_de_contas,id',
        ]);

        $categoria->update($request->all());
        return redirect()->route('categorias.index')->with('alert-success', 'Categoria atualizada com sucesso!');
    }

    // Excluir categoria
    public function destroy(CategoriaContas $categoria)
    {
        $categoria->delete();
        return redirect()->route('categorias.index')->with('alert-success', 'Categoria excluída com sucesso!');
    }
}
