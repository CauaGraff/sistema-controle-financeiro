<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LancamentoRecorrenciaConfig;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class LancamentoRecorrenciaController extends Controller
{
    // Exibe todas as recorrências
    public function index()
    {
        $recorrencias = LancamentoRecorrenciaConfig::all();
        return view('recorrencias.index', compact('recorrencias'));
    }

    // Exibe o formulário de edição da recorrência
    public function edit($id)
    {
        $recorrencia = LancamentoRecorrenciaConfig::findOrFail($id);
        return view('recorrencias.edit', compact('recorrencia'));
    }

    // Atualiza a recorrência no banco de dados
    public function update(Request $request, $id)
    {
        // Validação
        $validator = Validator::make($request->all(), [
            'descricao' => 'required',
            'data_inicio' => 'required',
            'data_fim' => 'nullable',
            'valor' => 'required',
            'tipo_recorrencia' => 'required|in:diaria,semanal,mensal,anual',
            'id_categoria' => 'required',
            'id_fornecedor_cliente' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->route('recorrencias.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        // Atualiza a recorrência
        $recorrencia = LancamentoRecorrenciaConfig::findOrFail($id);
        $recorrencia->update([
            'descricao' => $request->descricao,
            'data_inicio' => Carbon::parse($request->data_inicio),
            'data_fim' => $request->data_fim ? Carbon::parse($request->data_fim) : null,
            'valor' => $request->valor,
            'tipo_recorrencia' => $request->tipo_recorrencia,
            'id_categoria' => $request->id_categoria,
            'id_empresa' => $request->id_empresa,
            'id_fornecedor_cliente' => $request->id_fornecedor_cliente,
        ]);

        return redirect()->route('recorrencias.index')->with('success', 'Recorrência atualizada com sucesso!');
    }

    // Exclui a recorrência
    public function destroy($id)
    {
        // Encontra e exclui a recorrência
        $recorrencia = LancamentoRecorrenciaConfig::findOrFail($id);
        $recorrencia->delete();

        return redirect()->route('recorrencias.index')->with('success', 'Recorrência excluída com sucesso!');
    }
}
