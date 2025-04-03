<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\ContaBanco;
use App\Models\Lancamento;
use Illuminate\Http\Request;
use App\Models\LancamentoBaixa;
use App\Models\FornecedorCliente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Models\LancamentoRecorrenciaConfig;
use App\Models\CategoriaContas; // Modelo de Plano de Contas
use App\Http\Controllers\CategoriaContasController;

class LancamentoController extends Controller
{
    // Método para listar todos os lançamentos (pagamentos)
    // Método para listar todos os lançamentos (pagamentos)
    public function indexPagamentos(Request $request)
    {
        $empresaId = session('empresa_id');
        $query = Lancamento::where('id_empresa', $empresaId)->where('tipo', 'P');

        // Definir o período como o mês atual, caso não fornecido
        $startDate = $request->has('data_inicio') && $request->data_inicio != ''
            ? Carbon::parse($request->data_inicio)->startOfDay()
            : Carbon::now()->startOfMonth(); // Data de início do mês atual

        $endDate = $request->has('data_fim') && $request->data_fim != ''
            ? Carbon::parse($request->data_fim)->endOfDay()
            : Carbon::now()->endOfMonth(); // Data de fim do mês atual

        // Aplicar filtros de data
        $query->whereBetween('data_venc', [$startDate, $endDate]);

        // Filtros adicionais
        if ($request->has('valor_min') && $request->valor_min != '') {
            $query->where('valor', '>=', str_replace(['.', ','], ['', '.'], $request->valor_min));
        }

        if ($request->has('valor_max') && $request->valor_max != '') {
            $query->where('valor', '<=', str_replace(['.', ','], ['', '.'], $request->valor_max));
        }

        if ($request->has('categoria_id') && $request->categoria_id != '') {
            $query->where('id_categoria', $request->categoria_id);
        }

        if ($request->has('fornecedor_cliente_id') && $request->fornecedor_cliente_id != '') {
            $query->where('id_fornecedor_cliente', $request->fornecedor_cliente_id);
        }

        // Filtro de Status (Pago, Em Aberto, Vencido)
        if ($request->has('status') && $request->status != '') {
            $status = $request->status;

            if ($status == 'pago') {
                // Lançamentos pagos possuem uma baixa registrada
                $query->whereHas('lancamentoBaixaFilter', function ($query) {
                    $query->whereNotNull('id_lancamento');
                });
            } elseif ($status == 'em_aberto') {
                // Lançamentos em aberto não possuem uma baixa registrada
                $query->whereDoesntHave('lancamentoBaixaFilter');
            } elseif ($status == 'vencido') {
                // Lançamentos vencidos sem baixa registrada
                $query->where('data_venc', '<', Carbon::today())
                    ->whereDoesntHave('lancamentoBaixaFilter');
            }
        }

        // Recupera os lançamentos
        $lancamentos = $query->get();

        // Recupera categorias e fornecedores/clientes para exibição
        $route = "P";
        $categorias = CategoriaContas::where("id_empresa", session('empresa_id'))
            ->whereNull('id_categoria_pai') // Apenas categorias raiz
            ->with('subcategorias') // Carrega subcategorias automaticamente
            ->get();

        $fornecedoresClientes = FornecedorCliente::where("id_empresa", $empresaId)->get();

        return view('lancamentos.index', compact('lancamentos', 'categorias', 'route', 'fornecedoresClientes'));
    }

    public function indexRecebimentos(Request $request)
    {
        $empresaId = session('empresa_id');
        $query = Lancamento::where('id_empresa', $empresaId)->where('tipo', 'R');

        // Definir o período como o mês atual, caso não fornecido
        $startDate = $request->has('data_inicio') && $request->data_inicio != ''
            ? Carbon::parse($request->data_inicio)->startOfDay()
            : Carbon::now()->startOfMonth(); // Data de início do mês atual

        $endDate = $request->has('data_fim') && $request->data_fim != ''
            ? Carbon::parse($request->data_fim)->endOfDay()
            : Carbon::now()->endOfMonth(); // Data de fim do mês atual

        // Aplicar filtros de data
        $query->whereBetween('data_venc', [$startDate, $endDate]);

        // Filtros adicionais
        if ($request->has('valor_min') && $request->valor_min != '') {
            $query->where('valor', '>=', str_replace(['.', ','], ['', '.'], $request->valor_min));
        }

        if ($request->has('valor_max') && $request->valor_max != '') {
            $query->where('valor', '<=', str_replace(['.', ','], ['', '.'], $request->valor_max));
        }

        if ($request->has('categoria_id') && $request->categoria_id != '') {
            $query->where('id_categoria', $request->categoria_id);
        }

        if ($request->has('fornecedor_cliente_id') && $request->fornecedor_cliente_id != '') {
            $query->where('id_fornecedor_cliente', $request->fornecedor_cliente_id);
        }

        // Filtro de Status (Pago, Em Aberto, Vencido)
        if ($request->has('status') && $request->status != '') {
            $status = $request->status;

            if ($status == 'pago') {
                // Lançamentos pagos possuem uma baixa registrada
                $query->whereHas('lancamentoBaixaFilter', function ($query) {
                    $query->whereNotNull('id_lancamento');
                });
            } elseif ($status == 'em_aberto') {
                // Lançamentos em aberto não possuem uma baixa registrada
                $query->whereDoesntHave('lancamentoBaixaFilter');
            } elseif ($status == 'vencido') {
                // Lançamentos vencidos sem baixa registrada
                $query->where('data_venc', '<', Carbon::today())
                    ->whereDoesntHave('lancamentoBaixaFilter');
            }
        }

        // Recupera os lançamentos
        $lancamentos = $query->get();

        // Recupera categorias e fornecedores/clientes para exibição
        $route = "R";
        $categorias = CategoriaContas::where("id_empresa", session('empresa_id'))
            ->whereNull('id_categoria_pai') // Apenas categorias raiz
            ->with('subcategorias') // Carrega subcategorias automaticamente
            ->get();

        $fornecedoresClientes = FornecedorCliente::where("id_empresa", $empresaId)->get();

        return view('lancamentos.index', compact('lancamentos', 'categorias', 'route', 'fornecedoresClientes'));
    }

    public function create(Request $request)
    {
        $empresaId = session('empresa_id');
        // Obtém os dados necessários para o formulário
        $categorias = CategoriaContas::whereNull('id_categoria_pai')->with('subcategorias')->where("id_empresa", "=", session("empresa_id"))->get();

        $fornecedores = FornecedorCliente::where("id_empresa", $empresaId)->where("tipo", "F")->get();
        $clientes = FornecedorCliente::where("id_empresa", $empresaId)->where("tipo", "C")->get();

        if ($request->path() == "lancamentos/pagamentos/create") {
            return view('lancamentos.createPagamentos', compact('categorias', 'fornecedores', 'clientes'));
        } else {
            return view('lancamentos.formCreateRec', compact('categorias', 'fornecedores', 'clientes'));
        }
    }

    public function store(Request $request)
    {
        $typeLancamento = $request->path() == "lancamentos/pagamentos/store" ? "P" : "R";
        if ($request->tipo == 0) {
            $validated = $request->validate(
                [
                    'descricao' => 'required',
                    'valor' => 'required',
                    'data' => 'required',
                    'categoria_id' => 'required',
                ],
                [
                    'descricao.required' => 'Preencha a Descrição',
                    'valor.required' => 'Preencha o valor',
                    'data.required' => 'Preencha a Data de Vencimento',
                ]
            );
            Lancamento::create([
                'descricao' => $request->descricao,
                'valor' => str_replace(['.', ','], ['', '.'], $request->valor),  // Formatação do valor
                'tipo' => $typeLancamento,
                'data_venc' => $request->data,
                'id_categoria' => $request->categoria_id,
                'id_empresa' => session('empresa_id'),
                'id_fornecedor_cliente' => $request->fornecedor_cliente_id,
            ]);
            return redirect()->route($typeLancamento == "P" ? 'lancamentos.pagamentos.index' : 'lancamentos.recebimentos.index')->with('alert-success', "Cadastrado com sucesso!");
        }
        if ($request->tipo == 1) {
            $validated = $request->validate(
                [
                    'descricao' => 'required',
                    'dataVencPar' => 'required',
                    'categoria_id' => 'required',
                    'valorEntrada' => 'required',
                    'qtdParcelas' => 'required',
                    'valorTotal' => 'required'
                ],
                [
                    'descricao.required' => 'Preencha a Descrição',
                    'dataVencPar.required' => 'Preencha a 1º Data de Vencimento',
                    'valorEntrada.required' => 'Preencha o Valor da Entrada',
                    'qtdParcelas.required' => 'Preencha a Quantidade de Parcelas',
                    'valorTotal.required' => 'Preencha o Valor da Entrada'
                ]
            );
            $lancamento = Lancamento::create([
                'descricao' => $request->descricao . ' - Entrada',
                'valor' => str_replace(['.', ','], ['', '.'], $request->valorEntrada),  // Formatação do valor
                'tipo' => $typeLancamento,
                'data_venc' => date("Y-m-d"),
                'id_categoria' => $request->categoria_id,
                'id_empresa' => session('empresa_id'),
                'id_fornecedor_cliente' => $request->fornecedor_cliente_id,
            ]);
            LancamentoBaixa::create([
                'valor' => str_replace(['.', ','], ['', '.'], $request->valorEntrada),  // Formatação do valor
                'id_lancamento' => $lancamento->id,
                'id_contaBancaria' => ContaBanco::where('id_empresa', session('empresa_id'))->first()->id,
            ]);
            if ($request->qtdParcelas > 1) {
                $valorTotal = str_replace(['.', ','], ['', '.'], $request->valorTotal);
                $valorEntrada = str_replace(['.', ','], ['', '.'], $request->valorEntrada);
                $valorParcela = ($valorTotal - $valorEntrada) / $request->qtdParcelas;
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
                return redirect()->route($typeLancamento == "P" ? 'lancamentos.pagamentos.index' : 'lancamentos.recebimentos.index')->with('alert-success', "Cadastrado com sucesso!");
            }
            return redirect()->route($typeLancamento == "P" ? 'lancamentos.pagamentos.index' : 'lancamentos.recebimentos.index')->with('alert-danger', "Cadastrado com sucesso!");
        }
        if ($request->tipo == 2) {
            // Ajustando os campos de validação e removendo o dd()
            $validated = $request->validate([
                'descricao' => 'required|max:255',
                'frequencia' => 'required|in:diaria,semanal,mensal,anual',
                'data' => 'required',
                'data_fim' => 'nullable|after_or_equal:data',
                'valor' => 'required',
                'categoria_id' => 'required|exists:categorias_de_contas,id',
            ]);
            // Criar configuração de recorrência
            $recorrencia = LancamentoRecorrenciaConfig::create([
                'descricao' => $request->descricao,
                'tipo_recorrencia' => $request->frequencia, // Campo correto no formulário
                'data_inicio' => $request->data,
                'data_fim' => $request->data_fim,
                'valor' => str_replace(['.', ','], ['', '.'], $request->valor), // Formatação do valor
                'id_categoria' => $request->categoria_id,
                'id_empresa' => session('empresa_id'),
                'id_fornecedor_cliente' => $request->fornecedor_cliente_id,
                'ativo' => true,
                'tipo' => $typeLancamento,
            ]);
            return redirect()->route($typeLancamento == "P" ? 'lancamentos.pagamentos.index' : 'lancamentos.recebimentos.index')->with('alert-success', "Cadastrado com sucesso!");
        }
        return redirect()->route($typeLancamento == "P" ? 'lancamentos.pagamentos.index' : 'lancamentos.recebimentos.index')
            ->with('alert-danger', "Erro ao cadastrar o lançamento.");
    }
    public function edit(Lancamento $lancamento)
    {
        // Verificar se o lançamento está baixado
        $lancamentoBaixa = $lancamento->lancamentoBaixa;

        // Carregar categorias e subcategorias de forma hierárquica
        $categorias = CategoriaContas::where("id_empresa", session('empresa_id'))
            ->whereNull('id_categoria_pai') // Carregar apenas categorias raiz
            ->with('subcategorias') // Carregar as subcategorias
            ->get();

        $fornecedores = FornecedorCliente::where("id_empresa", session('empresa_id'))->get();
        $clientes = FornecedorCliente::where("id_empresa", session('empresa_id'))->get();

        return view('lancamentos.formEdit', compact('lancamento', 'categorias', 'fornecedores', 'clientes', 'lancamentoBaixa'));
    }

    // Função para atualizar um lançamento
    public function update(Request $request, $id)
    {
        // Verifica se o lançamento foi baixado
        $lancamento = Lancamento::findOrFail($id);
        $lancamentoBaixa = $lancamento->lancamentoBaixa;
        // Definir as regras de validação com base no estado de "baixado"
        // if ($lancamentoBaixa) {
        //     // Se já foi baixado, somente permita editar a descrição, categoria, fornecedor/cliente, número do documento e anexo
        //     $validated = $request->validate([
        //         'descricao' => 'required|string|max:255',
        //         'categoria_id' => 'required|exists:categorias_de_contas,id',
        //         'fornecedor_cliente_id' => 'required|exists:fornecedor_cliente,id',
        //         'anexo' => 'mimes:pdf,jpeg,png,jpg|max:2048',
        //     ]);
        // } else {
        //     // Se não foi baixado, permite editar todos os campos
        //     $validated = $request->validate([
        //         'descricao' => 'required|string|max:255',
        //         'valor' => 'required|min:0',
        //         'data' => 'required|date',
        //         'categoria_id' => 'required|exists:categorias_de_contas,id',
        //         'fornecedor_cliente_id' => 'required|exists:fornecedor_cliente,id',
        //         'numero_documento' => 'nullable|string|max:255',
        //         'anexo' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
        //     ]);
        // }

        // Atualiza os dados principais do lançamento
        $lancamento->descricao = $request->descricao;
        $lancamento->id_categoria = $request->categoria_id;
        $lancamento->id_fornecedor_cliente = $request->fornecedor_cliente_id;

        // Se o lançamento não foi baixado, permite editar o valor e a data
        if (!$lancamentoBaixa) {
            $lancamento->valor = $this->formatarValor($request->valor);
            $lancamento->data_venc = $request->data;
        }
        // Verifica se há um anexo para upload
        if ($request->hasFile('anexo') && $request->file('anexo')->isValid()) {

            // Remove o anexo antigo, se houver
            if ($lancamentoBaixa && $lancamentoBaixa->anexo) {
                Storage::disk('public')->delete($lancamentoBaixa->anexo);
            }
            // Gerar um nome único para o arquivo, incluindo o ID do lançamento e o timestamp
            $fileName = $lancamento->id . '_' . session('empresa_id') . '_' . now()->format('YmdHis') . "_" . uniqid() . '.' . $request->file('anexo')->extension();

            // Armazenar o arquivo no diretório 'public/anexos' (pode ser ajustado conforme necessário)
            $anexoPath = $request->file('anexo')->storeAs('anexos', $fileName, 'public'); // 'public' é o disco configurado no config/filesystems.php

            // Faz o upload do novo anexo
            if ($lancamentoBaixa) {
                // Atualiza o anexo no LancamentoBaixa
                $lancamentoBaixa->anexo = $anexoPath;
                $lancamentoBaixa->save();
            }
        }

        // Atualiza o número do documento, se fornecido
        if ($lancamentoBaixa) {
            $lancamentoBaixa->doc = $request->numero_documento ?? $lancamentoBaixa->doc;
            $lancamentoBaixa->save();
        }

        // Se o lançamento já foi baixado, atualiza apenas os campos permitidos
        $lancamento->save();

        // Retorna para a página de edição com uma mensagem de sucesso
        return redirect()->route($lancamento->tipo = 'P' ? 'lancamentos.pagamentos.index' : 'lancamentos.recebimentos.index')
            ->with('success', 'Lançamento atualizado com sucesso!');
    }
    public function destroy(Lancamento $lancamento)
    {
        // Buscar o lançamento e suas baixas associadas
        $lancamento = Lancamento::findOrFail($lancamento->id);
        // Buscar as baixas associadas a esse lançamento
        $lancamentosBaixa = LancamentoBaixa::where('id_lancamento', $lancamento->id)->get();
        // Verificar se existe um anexo para essa baixa
        foreach ($lancamentosBaixa as $baixa) {
            // Verificar se existe um anexo para essa baixa
            if ($baixa->anexo) {
                // Verificar na pasta se exise o arquivo
                if (Storage::disk('public')->exists($baixa->anexo)) {
                    // Excluir o arquivo do sistema de arquivos
                    $this->deleteFileFromStorage($baixa->id);
                }
            }
            // Excluir a baixa após remover o anexo
            $baixa->delete();
        }
        $lancamento->delete();
        return redirect()->route($lancamento->tipo = 'P' ? 'lancamentos.pagamentos.index' : 'lancamentos.recebimentos.index')
            ->with('success', 'Lançamento e anexo excluídos com sucesso!');
    }

    public function deleteBaixa(Lancamento $lancamento)
    {
        // Verificar se o lançamento tem uma baixa associada
        if ($lancamento->lancamentoBaixa) {
            if ($lancamento->lancamentoBaixa->anexo) {
                // Verificar na pasta se exise o arquivo
                if (Storage::disk('public')->exists($lancamento->lancamentoBaixa->anexo)) {
                    // Excluir o arquivo do sistema de arquivos
                    $this->deleteFileFromStorage($lancamento->lancamentoBaixa->id);
                }
            }
            $lancamento->lancamentoBaixa()->delete();
            return redirect()->route('lancamentos.edit', $lancamento->id)->with('alert-success', 'Baixa excluída com sucesso!');
        }

        return redirect()->route('lancamentos.edit', $lancamento->id)->with('alert-danger', 'Lançamento não tem baixa associada.');
    }
    public function formbaixa(Lancamento $lancamento)
    {
        // Recupera os parâmetros de cálculo
        $parametros = \App\Models\ParametrosCalculo::all();

        $contasBancarias = ContaBanco::where('id_empresa', session('empresa_id'))->get();

        // Calcular valores de juros, multa e desconto
        $juros = 0;
        $multa = 0;
        $desconto = 0;
        $valor = (float) $lancamento->valor;


        // Define os parâmetros de juros, multa e desconto (exemplo)
        $param_juros = $parametros->where('descricao', 'Juros')->first();  // exemplo: aplicação de juros
        $param_multa = $parametros->where('descricao', 'Multa')->first();  // exemplo: aplicação de multa
        $param_desconto = $parametros->where('descricao', 'Descontos')->first();  // exemplo: aplicação de desconto

        // Verificar se a data de vencimento já passou para aplicar juros e multa
        $data_vencimento = Carbon::parse($lancamento->data_venc);
        $data_atual = Carbon::now();

        // Garantir que a diferença de dias seja um valor inteiro
        $dias_em_atraso = intval($data_vencimento->diffInDays($data_atual));

        // Aplica juros e multa se houver atraso
        if ($dias_em_atraso > 0) {
            $juros = number_format(($valor * ($param_juros->indice / 100)) * $dias_em_atraso, 2);
            $multa = number_format(($valor * $param_multa->indice / 100), 2); // arredondando para 2 casas decimais
        }

        // Aplicar desconto se for dentro do prazo
        if ($dias_em_atraso < 0) {
            $desconto = number_format($valor * $param_desconto->indice / 100, 2);
        }


        // Calcular valor total a ser pago
        $valor_total = number_format((float) $valor + (float) $juros + (float) $multa - (float) $desconto, 2);
        // Passar os valores para a view
        return view('lancamentos.pagar', compact('lancamento', 'juros', 'multa', 'desconto', 'valor_total', 'data_atual', 'contasBancarias'));
    }

    public function baixaStore(Request $request, Lancamento $lancamento)
    {
        // Verificar se o lançamento já foi baixado (se já existe um registro na tabela LancamentoBaixa com o mesmo id_lancamento)
        $existeBaixa = LancamentoBaixa::where('id_lancamento', $lancamento->id)->exists();
        // Se o lançamento já foi baixado, retornar um erro ou mensagem de aviso
        if ($existeBaixa) {
            return redirect()->route($lancamento->tipo = 'p' ? 'lancamentos.pagamentos.index' : 'lancamentos.recebimentos.index')
                ->with('alert-danger', 'Este lançamento já foi baixado.');
        }
        // Validação
        $validate = $request->validate([
            'data_pagamento' => 'required|date',
            'contasBancarias' => 'required|exists:contas_banco,id',
            'anexo' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:2048',
        ], [
            'data_pagamento.required' => 'A data do pagamento é obrigatória.',
            'data_pagamento.date' => 'A data do pagamento deve estar em um formato válido.',
            'contasBancarias.required' => 'É necessário selecionar uma conta bancária.',
            'contasBancarias.exists' => 'A conta bancária selecionada não é válida.',
            'anexo.mimes' => 'O arquivo deve ser um PDF ou imagem (JPEG, JPG, PNG).',
            'anexo.max' => 'O arquivo não pode ultrapassar 2MB.',
        ]);

        // Tratar os valores que vêm com separadores de milhar e vírgula como separador decimal
        $valorPago = $this->formatarValor($request->valor_pago);
        $multa = $this->formatarValor($request->multa);
        $juros = $this->formatarValor($request->juros);
        $desconto = $this->formatarValor($request->desconto);
        // Processamento do arquivo (se houver)
        $anexoPath = null; // Inicializar a variável do caminho do arquivo
        if ($request->hasFile('anexo') && $request->file('anexo')->isValid()) {
            // Gerar um nome único para o arquivo, incluindo o ID do lançamento e o timestamp
            $fileName = $lancamento->id . '_' . session('empresa_id') . '_' . now()->format('YmdHis') . "_" . uniqid() . '.' . $request->file('anexo')->extension();

            // Armazenar o arquivo no diretório 'public/anexos' (pode ser ajustado conforme necessário)
            $anexoPath = $request->file('anexo')->storeAs('anexos', $fileName, 'public'); // 'public' é o disco configurado no config/filesystems.php
        }
        // Criar a baixa do lançamento com os valores formatados
        LancamentoBaixa::create([
            'id_lancamento' => $lancamento->id,
            'id_contaBancaria' => $request->contasBancarias,
            'valor' => $valorPago,
            'juros' => $juros,
            'multa' => $multa,
            'desconto' => $desconto,
            'doc' => $request->numero_documento,
            'anexo' => $anexoPath, // Caminho do arquivo armazenado
        ]);
        // Redirecionamento ou resposta após a criação
        return redirect()->route($lancamento->tipo == 'P' ? 'lancamentos.pagamentos.index' : 'lancamentos.recebimentos.index')
            ->with('success', 'Lançamento baixado com sucesso!');
    }

    // Função para formatar valores recebidos
    private function formatarValor($valor): float
    {
        // Remover os pontos (milhares) e substituir a vírgula por ponto decimal
        $valor = str_replace('.', '', $valor); // Remove o ponto dos milhares
        $valor = str_replace(',', '.', $valor); // Substitui a vírgula por ponto decimal

        // Retornar o valor formatado como float
        return (float) $valor;
    }

    public function deleteFileFromStorage($id)
    {
        $lancamentoBaixa = LancamentoBaixa::findOrFail($id);

        // Deletar o arquivo do armazenamento
        if (Storage::disk('public')->exists($lancamentoBaixa->anexo)) {
            Storage::disk('public')->delete($lancamentoBaixa->anexo);
        }

        // Atualizar o campo 'anexo' no banco de dados (opcional)
        $lancamentoBaixa->anexo = null;
        $lancamentoBaixa->save();

        // Redirecionar ou retornar uma resposta
        return redirect()->back()->with('success', 'Arquivo excluído com sucesso.');
    }

    public function export(Request $request)
    {
        $lancamentosQuery = Lancamento::with(['fornecedorCliente', 'categoriaContas', 'lancamentoBaixa.contaBancaria'])
            ->where('id_empresa', session('empresa_id'))
            ->where('tipo', $request->route);

        if ($startDate = $request->data_inicio) {
            $lancamentosQuery->where('data_venc', '>=', Carbon::parse($startDate));
        }
        if ($endDate = $request->data_fim) {
            $lancamentosQuery->where('data_venc', '<=', Carbon::parse($endDate));
        }
        if ($valorMin = $request->valor_min) {
            $lancamentosQuery->where('valor', '>=', $valorMin);
        }
        if ($valorMax = $request->valor_max) {
            $lancamentosQuery->where('valor', '<=', $valorMax);
        }
        if ($categoriaId = $request->categoria_id) {
            $lancamentosQuery->where('id_categoria', $categoriaId);
        }
        if ($fornecedorClienteId = $request->fornecedor_cliente_id) {
            $lancamentosQuery->where('id_fornecedor_cliente', $fornecedorClienteId);
        }
        if ($request->has('status') && $request->status != '') {
            $status = $request->status;

            if ($status == 'pago') {
                $lancamentosQuery->whereHas('lancamentoBaixaFilter', function ($query) {
                    $query->whereNotNull('id_lancamento');
                });
            } elseif ($status == 'em_aberto') {
                $lancamentosQuery->whereDoesntHave('lancamentoBaixaFilter');
            } elseif ($status == 'vencido') {
                $lancamentosQuery->where('data_venc', '<', Carbon::now())
                    ->whereDoesntHave('lancamentoBaixaFilter');
            }
        }

        $lancamentos = $lancamentosQuery->get();

        if ($lancamentos->isEmpty()) {
            return response()->json(['message' => 'Nenhum lançamento encontrado para exportação.'], 404);
        }

        $csvContent = '';
        $csvHeader = ['Nº', 'Descrição', 'Data Vencimento', 'Valor', 'Fornecedor/Cliente', 'Categoria', 'Data de Baixa', 'Valor Baixado', 'Conta Bancária', 'Número do Documento'];
        $csvContent .= implode(';', $csvHeader) . "\n";

        $formatMoney = function ($value) {
            return $value !== null ? number_format($value, 2, ',', '.') : '';
        };

        foreach ($lancamentos as $lancamento) {
            $fornecedorCliente = $lancamento->fornecedorCliente->nome ?? '';
            $categoria = $lancamento->categoriaContas->descricao ?? '';
            $dataBaixa = $lancamento->lancamentoBaixa->updated_at ?? null;
            $valorBaixado = $lancamento->lancamentoBaixa->valor ?? null;
            $contaBancaria = $lancamento->lancamentoBaixa->contaBancaria->nome ?? '';
            $numeroDocumento = $lancamento->lancamentoBaixa->doc ?? '';

            $csvContent .= implode(';', [
                $lancamento->id,
                $lancamento->descricao,
                $lancamento->data_venc ? $lancamento->data_venc->format('d/m/Y') : '',
                $formatMoney($lancamento->valor),
                $fornecedorCliente,
                $categoria,
                $dataBaixa ? $dataBaixa->format('d/m/Y') : '',
                $formatMoney($valorBaixado),
                $contaBancaria,
                $numeroDocumento
            ]) . "\n";
        }

        $fileName = 'lancamentos_' . time() . '.csv';

        // Define os cabeçalhos para download
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->header('Cache-Control', 'no-store, no-cache');
    }

}
