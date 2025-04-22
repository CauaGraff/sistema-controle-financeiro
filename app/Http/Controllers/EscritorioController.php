<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Escritorio;
use Illuminate\Http\Request;

class EscritorioController extends Controller
{
    public function index()
    {
        $escritorios = Escritorio::orderBy('id')->get();
        return view('admin.escritorios.index', compact('escritorios'));
    }

    public function create()
    {
        return view('admin.escritorios.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'cnpj' => 'nullable|string|max:20',
            'cep' => 'nullable|string|size:8',
            'rua' => 'nullable|string|max:255',
            'bairro' => 'nullable|string|max:255',
            'cidade' => 'nullable|string|max:255',
            'uf' => 'nullable|string|size:2',
            'complemento' => 'nullable|string|max:255',
            'obs' => 'nullable|string',
            'active' => 'boolean',
        ]);

        Escritorio::create($data);

        return redirect()->route('escritorios.index')
            ->with('success', 'Escritório cadastrado com sucesso.');
    }

    public function show(Escritorio $escritorio)
    {
        return view('admin.escritorios.show', compact('escritorio'));
    }

    public function edit(Escritorio $escritorio)
    {
        return view('admin.escritorios.edit', compact('escritorio'));
    }

    public function update(Request $request, Escritorio $escritorio)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'cnpj' => 'nullable|string|max:20',
            'cep' => 'nullable|string|size:8',
            'rua' => 'nullable|string|max:255',
            'bairro' => 'nullable|string|max:255',
            'cidade' => 'nullable|string|max:255',
            'uf' => 'nullable|string|size:2',
            'complemento' => 'nullable|string|max:255',
            'obs' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $escritorio->update($data);

        return redirect()->route('escritorios.index')
            ->with('success', 'Escritório atualizado com sucesso.');
    }

    public function destroy(Escritorio $escritorio)
    {
        $escritorio->delete();

        return redirect()->route('escritorios.index')
            ->with('success', 'Escritório excluído com sucesso.');
    }
}
