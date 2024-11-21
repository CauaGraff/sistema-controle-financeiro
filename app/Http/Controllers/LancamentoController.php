<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CategoriaContas; // Modelo de Plano de Contas
use App\Models\Lancamento; // Supondo que você tenha um modelo de Lancamento
use App\Models\FornecedorCliente;

class LancamentoController extends Controller
{
    // Método para listar todos os lançamentos (pagamentos)
    public function indexPagamentos()
    {
        // Verifica se o usuário está autenticado e se tem permissão para acessar a empresa
        $empresaId = session('empresa_id');
        $lancamentos = Lancamento::where('id_empresa', $empresaId)->where('tipo', 'P')->get();

        return view('lancamentos.index', compact('lancamentos'));
    }

    // Método para listar todos os lançamentos (recebimentos)
    public function indexRecebimentos()
    {
        // Verifica se o usuário está autenticado e se tem permissão para acessar a empresa
        $empresaId = session('empresa_id');
        $recebimentos = Lancamento::where('id_empresa', $empresaId)->where('tipo', 'R')->get();

        return view('lancamentos.recebimentos.index', compact('recebimentos'));
    }

    public function create()
    {
        $empresaId = session('empresa_id');
        // Obtém os dados necessários para o formulário
        $categorias = CategoriaContas::where("id_empresa", $empresaId)->get(); // Supondo que você tenha um modelo de CategoriaContas
        // Agrupar categorias por pai
        $categoriasAgrupadas = [];

        // Primeiro, organize categorias em um array por ID
        foreach ($categorias as $categoria) {
            if ($categoria->id_categoria_pai) {
                // Se a categoria tem um pai, adicione-a à lista do pai correspondente
                $categoriasAgrupadas[$categoria->id_categoria_pai]['subcategorias'][] = $categoria;
            } else {
                // Se a categoria não tem pai, é uma categoria principal
                $categoriasAgrupadas[$categoria->id]['categoria'] = $categoria;
            }
        }

        $fornecedores = FornecedorCliente::where("id_empresa", $empresaId)->get();
        $clientes = FornecedorCliente::where("id_empresa", $empresaId)->get();
        return view('lancamentos.create', compact('categoriasAgrupadas', 'fornecedores', 'clientes'));
    }

    public function store(Request $request)
    {
        if ($request->path() == "lancamentos/pagamentos") {
            if ($request->tipo == 0) {
                // $validate = $request->validate([
                //     'descricao' => 'required',
                //     'valor' => 'required',
                //     'data_vencimento' => 'required',
                //     'categoria_id' => 'required',
                //     'favorecido' => 'required'
                // ]);

                return dd(Lancamento::create([
                    $request->all()
                ]));
            }
        }
        // return redirect()->route('lancamentos.pagamentos.index')->with('success', 'Lançamento criado com sucesso!');
    }

    public function edit(Lancamento $lancamento)
    {
        // Obtém os dados necessários para o formulário
        $categorias = CategoriaContas::where("id_empresa", section())->get(); // Supondo que você tenha um modelo de CategoriaContas
        return view('lancamentos.edit', compact('lancamento', 'planosDeContas'));
    }

    public function update(Request $request, Lancamento $lancamento)
    {
        // Validação dos dados
        $request->validate([
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric',
            'tipo' => 'required|in:R,P',
            'data_venc' => 'required|date',
            'id_plano_contas' => 'required|exists:plano_de_contas,id',
        ]);

        // Atualiza o lançamento
        $lancamento->update($request->all());

        return redirect()->route('lancamentos.pagamentos.index')->with('success', 'Lançamento atualizado com sucesso!');
    }

    public function destroy(Lancamento $lancamento)
    {
        // Exclui o lançamento
        $lancamento->delete();
        return redirect()->route('lancamentos.pagamentos.index')->with('success', 'Lançamento excluído com sucesso!');
    }

    public function baixa(Lancamento $lancamento)
    {
        // Atualiza a data de baixa
        $lancamento->data_baixa = now();
        $lancamento->save();

        return redirect()->route('lancamentos.pagamentos.index')->with('success', 'Baixa realizada com sucesso!');
    }
}
