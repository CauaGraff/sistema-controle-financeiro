<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\FornecedorCliente;
use Illuminate\Support\Facades\Auth;
use App\Models\CategoriaContas; // Modelo de Plano de Contas
use App\Models\Lancamento;
use Route;// Supondo que você tenha um modelo de Lancamento

class LancamentoController extends Controller
{
    // Método para listar todos os lançamentos (pagamentos)
    public function indexPagamentos()
    {
        // Verifica se o usuário está autenticado e se tem permissão para acessar a empresa
        $empresaId = session('empresa_id');
        $lancamentos = Lancamento::where('id_empresa', $empresaId)->where('tipo', 'P')->get();
        $route = "P";
        return view('lancamentos.index', compact('lancamentos', 'route'));
    }

    // Método para listar todos os lançamentos (recebimentos)
    public function indexRecebimentos()
    {
        $empresaId = session('empresa_id');
        $lancamentos = Lancamento::where('id_empresa', $empresaId)->where('tipo', 'R')->get();
        $route = "R";
        return view('lancamentos.index', compact('lancamentos', 'route'));
    }

    public function create(Request $request)
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
        $typeCadastro = $request->path() == "lancamentos/pagamentos/create" ? "P" : "R";

        $fornecedores = FornecedorCliente::where("id_empresa", $empresaId)->get();
        $clientes = FornecedorCliente::where("id_empresa", $empresaId)->get();

        return view('lancamentos.create', compact('categoriasAgrupadas', 'fornecedores', 'clientes', 'typeCadastro'));
    }

    public function store(Request $request)
    {
        $typeLancamento = $request->path() == "lancamentos/pagamentos/store" ? "P" : "R";
        if ($request->tipo == 0) {
            Lancamento::create([
                'descricao' => $request->descricao,
                'valor' => str_replace(['.', ','], ['', '.'], $request->valor),  // Formatação do valor
                'tipo' => $typeLancamento,
                'data_venc' => $request->data,
                'id_categoria' => $request->categoria_id,
                'id_empresa' => session('empresa_id'),
                'id_fornecedor_cliente' => $request->fornecedor_cliente_id,
            ]);
            return redirect()->route('lancamentos.pagamentos.index')->with('success', 'Lançamento criado com sucesso!');
        }

        if ($request->tipo == 1) {

            Lancamento::create([
                'descricao' => $request->descricao . ' - Entrada',
                'valor' => str_replace(['.', ','], ['', '.'], $request->valorEntrada),  // Formatação do valor
                'tipo' => $typeLancamento,
                'data_venc' => $request->dataVencPar,
                'id_categoria' => $request->categoria_id,
                'id_empresa' => session('empresa_id'),
                'id_fornecedor_cliente' => $request->fornecedor_cliente_id,
            ]);
            if ($request->qtdParcelas > 1) {
                $valorTotal = str_replace(['.', ','], ['', '.'], $request->valorTotal);
                $valorEntrada = str_replace(['.', ','], ['', '.'], $request->valorEntrada);
                $valorParcela = ($valorTotal - $valorEntrada) / $request->qtdParcelas;

                // dd($valorTotal, $valorEntrada, $valorParcela);

                $dataVencimento = Carbon::parse($request->dataVencPar);

                for ($i = 1; $i <= $request->qtdParcelas; $i++) {
                    $novaDataVencimento = $dataVencimento->copy()->addMonths($i - 1);

                    Lancamento::create([
                        'descricao' => $request->descricao . ' - Parcela ' . $i,
                        'valor' => $valorParcela,
                        'tipo' => $typeLancamento,
                        'id_categoria' => $request->categoria_id,
                        'data_venc' => $novaDataVencimento,
                        'id_empresa' => session('empresa_id'),
                        'id_fornecedor_cliente' => $request->fornecedor_cliente_id
                    ]);
                }
                return redirect()->route('lancamentos.pagamentos.index')->with('success', 'Lançamento criado com sucesso!');
            }
            if ($request->tipo == 2) {
                // Criação do lançamento inicial
                $lancamento = Lancamento::create([
                    'descricao' => $request->descricao,
                    'valor' => $request->valor,
                    'data_venc' => $request->data_venc,
                    'recorrente' => $request->recorrente ?? false,
                    'tipo_recorrencia' => $request->recorrente ? $request->tipo_recorrencia : null,
                    'frequencia' => $request->recorrente ? $request->frequencia : null,
                ]);

                // Se for recorrente, criar os próximos lançamentos
                if ($lancamento->recorrente) {
                    $this->criarLançamentosRecorrentes($lancamento);
                }

                return redirect()->route('lancamentos.pagamentos.index');
            }
            return redirect()->route('lancamentos.pagamentos.index')->with('success', 'Lançamento criado com sucesso!');
        }
        // return redirect()->route('lancamentos.pagamentos.index')->with('success', 'Lançamento criado com sucesso!');

    }

    private function criarLançamentosRecorrentes(Lancamento $lancamento)
    {
        $proximoVencimento = Carbon::parse($lancamento->data_venc);

        // Por exemplo, criar 12 lançamentos futuros (1 para cada mês, por 1 ano)
        $quantidadeDeLançamentos = 12;  // Você pode ajustar esse número conforme a necessidade (ano inteiro, por exemplo)

        for ($i = 1; $i <= $quantidadeDeLançamentos; $i++) {
            switch ($lancamento->tipo_recorrencia) {
                case 'diario':
                    $proximoVencimento->addDay();
                    break;
                case 'semanal':
                    $proximoVencimento->addWeek();
                    break;
                case 'mensal':
                    $proximoVencimento->addMonth();
                    break;
                case 'anual':
                    $proximoVencimento->addYear();
                    break;
            }

            // Cria o próximo lançamento com a data calculada
            Lancamento::create([
                'descricao' => $lancamento->descricao,
                'valor' => $lancamento->valor,
                'data_venc' => $proximoVencimento->format('Y-m-d'), // Formato adequado para o banco
                'recorrente' => true,
                'tipo_recorrencia' => $lancamento->tipo_recorrencia,
                'frequencia' => $lancamento->frequencia,
            ]);
        }
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

    public function Formbaixa(Lancamento $lancamento)
    {
        dd($lancamento);

        // return redirect()->route('lancamentos.pagamentos.index')->with('success', 'Baixa realizada com sucesso!');
    }

    public function baixaStore()
    {

    }
}
