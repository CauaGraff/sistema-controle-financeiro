<?php

namespace App\Http\Controllers;

use App\Models\ContaBanco;
use Carbon\Carbon;
use App\Models\Lancamento;
use Illuminate\Http\Request;
use App\Models\LancamentoBaixa;
use App\Models\FornecedorCliente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\CategoriaContas; // Modelo de Plano de Contas

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
        $fornecedores = FornecedorCliente::where("id_empresa", $empresaId)->get();
        $clientes = FornecedorCliente::where("id_empresa", $empresaId)->get();

        if ($request->path() == "lancamentos/pagamentos/create") {
            return view('lancamentos.createPagamentos', compact('categoriasAgrupadas', 'fornecedores', 'clientes'));
        } else {
            return view('lancamentos.formCreateRec', compact('categoriasAgrupadas', 'fornecedores', 'clientes'));
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
            // if ($request->tipo == 2) {
            //     // Criação do lançamento inicial
            //     $lancamento = Lancamento::create([
            //         'descricao' => $request->descricao,
            //         'valor' => $request->valor,
            //         'data_venc' => $request->data_venc,
            //         'recorrente' => $request->recorrente ?? false,
            //         'tipo_recorrencia' => $request->recorrente ? $request->tipo_recorrencia : null,
            //         'frequencia' => $request->recorrente ? $request->frequencia : null,
            //     ]);
            //     // Se for recorrente, criar os próximos lançamentos
            //     if ($lancamento->recorrente) {
            //         $this->criarLançamentosRecorrentes($lancamento);
            //     }
            //     return redirect()->route($typeLancamento == "P" ? 'lancamentos.pagamentos.index' : 'lancamentos.recebimentos.index')->with('alert-success', "Cadastrado com sucesso!");
            // }
            return redirect()->route($typeLancamento == "P" ? 'lancamentos.pagamentos.index' : 'lancamentos.recebimentos.index')->with('alert-danger', "Cadastrado com sucesso!");
        }
        // return redirect()->route('lancamentos.pagamentos.index')->with('success', 'Lançamento criado com sucesso!');
    }

    private function criarLançamentosRecorrentes(Lancamento $lancamento)
    {
        $proximoVencimento = Carbon::parse($lancamento->data_venc);
        $quantidadeDeLançamentos = 12;
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
        // Verificar se o lançamento está baixado
        $lancamentoBaixa = $lancamento->lancamentoBaixa;

        // Outros dados necessários
        $categorias = CategoriaContas::where("id_empresa", session('empresa_id'))->get();
        $categoriasAgrupadas = [];
        foreach ($categorias as $categoria) {
            if ($categoria->id_categoria_pai) {
                $categoriasAgrupadas[$categoria->id_categoria_pai]['subcategorias'][] = $categoria;
            } else {
                $categoriasAgrupadas[$categoria->id]['categoria'] = $categoria;
            }
        }
        $fornecedores = FornecedorCliente::where("id_empresa", session('empresa_id'))->get();
        $clientes = FornecedorCliente::where("id_empresa", session('empresa_id'))->get();

        return view('lancamentos.formEdit', compact('lancamento', 'categoriasAgrupadas', 'fornecedores', 'clientes', 'lancamentoBaixa'));
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

            // Verificar na pasta se exise o arquivo
            if (Storage::disk('public')->exists($lancamento->lancamentoBaixa->anexo)) {
                // Excluir o arquivo do sistema de arquivos
                $this->deleteFileFromStorage($lancamento->lancamentoBaixa->id);
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
            $juros = number_format(($lancamento->valor * ($param_juros->indice / 100)) * $dias_em_atraso, 2);
            $multa = number_format(($lancamento->valor * $param_multa->indice / 100), 2); // arredondando para 2 casas decimais
        }

        // Aplicar desconto se for dentro do prazo
        if ($dias_em_atraso < 0) {
            $desconto = number_format($lancamento->valor * $param_desconto->indice / 100, 2);
        }
        // Calcular valor total a ser pago
        $valor_total = number_format($lancamento->valor + $juros + $multa - $desconto, 2);

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
            'data_pagamento' => 'required',
            'contasBancarias' => 'required|exists:contas_bancarias,id',
            'anexo' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:2048', // Validação para o arquivo (opcional)
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
            'id_contaBancaria' => $request->conta_bancaria,
            'valor' => $valorPago,
            'juros' => $juros,
            'multa' => $multa,
            'desconto' => $desconto,
            'doc' => $request->numero_documento,
            'anexo' => $anexoPath, // Caminho do arquivo armazenado
        ]);
        // Redirecionamento ou resposta após a criação
        return redirect()->route($lancamento->tipo = 'P' ? 'lancamentos.pagamentos.index' : 'lancamentos.recebimentos.index')
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
}
