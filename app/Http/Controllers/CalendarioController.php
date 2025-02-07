<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Lancamento;
use Illuminate\Http\Request;

class CalendarioController extends Controller
{

    // Exibe o calendário
    public function index()
    {
        return view('home');
    }

    // Gera os lançamentos para um mês específico via AJAX
    public function postEventos(Request $request)
    {
        $month = $request->get('month');
        $year = $request->get('year');

        // Se não for passado mês ou ano, usa o mês atual
        $month = $month ?: Carbon::now()->month;
        $year = $year ?: Carbon::now()->year;

        // Obtém o primeiro dia do mês e o último dia do mês, mas sem a hora
        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth()->endOfDay();

        // Recupera os lançamentos do mês
        $lancamentos = Lancamento::whereBetween('data_venc', [$startOfMonth, $endOfMonth])
            ->where('id_empresa', session('empresa_id'))
            ->get();

        // Organize os lançamentos para retornar no formato adequado
        $events = [];
        foreach ($lancamentos as $lancamento) {
            // A data de vencimento do lançamento
            $dataVenc = Carbon::parse($lancamento->data_venc)->startOfDay(); // Força o início do dia

            // A data atual sem hora (garante que estamos comparando somente a data, não a hora)
            $dataAtual = Carbon::now()->startOfDay();

            // A classe para indicar o status
            $statusClass = '';

            // Verifica se o lançamento foi pago
            if ($lancamento->lancamentoBaixa) {
                $statusClass = ''; // Se foi pago
            }
            // Verifica se a data de vencimento é hoje
            elseif ($dataVenc->isSameDay($dataAtual)) {
                $statusClass = 'table-warning'; // Se for hoje, está a vencer
            }
            // Verifica se a data de vencimento já passou
            elseif ($dataVenc->lt($dataAtual)) {
                $statusClass = 'table-danger'; // Se a data de vencimento já passou, está vencido
            }
            // Caso contrário, está a vencer no futuro
            else {
                $statusClass = 'table-warning'; // A vencer no futuro
            }

            $events[] = [
                'date' => $dataVenc->toDateString(), // Apenas a data, sem hora
                'statusClass' => $statusClass, // Passa a classe de cor
            ];
        }
        return response()->json($events);
    }

    // Consulta os lançamentos e retorna um JSON dentro do período especificado
    public function postLancamentos(Request $request)
    {
        $date = $request->get('date'); // Recebe a data específica
        $month = $request->get('month');
        $year = $request->get('year');

        // Se não for passado mês ou ano, usa o mês atual
        $month = $month ?: Carbon::now()->month;
        $year = $year ?: Carbon::now()->year;

        // Se a data foi fornecida, filtra os lançamentos para esse dia
        if ($date) {
            $startOfDay = Carbon::parse($date)->startOfDay(); // Início do dia
            $endOfDay = Carbon::parse($date)->endOfDay(); // Fim do dia

            // Recupera os lançamentos de pagamentos para a data selecionada
            $pagamentos = Lancamento::whereBetween('data_venc', [$startOfDay, $endOfDay])
                ->where('id_empresa', session('empresa_id'))
                ->where('tipo', 'P')
                ->get();

            // Recupera os lançamentos de recebimentos para a data selecionada
            $recebimentos = Lancamento::whereBetween('data_venc', [$startOfDay, $endOfDay])
                ->where('id_empresa', session('empresa_id'))
                ->where('tipo', 'R')
                ->get();
        } else {
            // Caso contrário, filtra por mês
            $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfDay();
            $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth()->endOfDay();

            // Recupera os lançamentos de pagamentos para o mês
            $pagamentos = Lancamento::whereBetween('data_venc', [$startOfMonth, $endOfMonth])
                ->where('id_empresa', session('empresa_id'))
                ->where('tipo', 'P')
                ->get();

            // Recupera os lançamentos de recebimentos para o mês
            $recebimentos = Lancamento::whereBetween('data_venc', [$startOfMonth, $endOfMonth])
                ->where('id_empresa', session('empresa_id'))
                ->where('tipo', 'R')
                ->get();
        }

        // Filtra os lançamentos com baixa para o cálculo dos totais
        $pagamentosComBaixa = $pagamentos->filter(function ($lancamento) {
            return $lancamento->lancamentoBaixa; // Filtra apenas os lançamentos com baixa
        });

        $recebimentosComBaixa = $recebimentos->filter(function ($lancamento) {
            return $lancamento->lancamentoBaixa; // Filtra apenas os lançamentos com baixa
        });

        // Calcular totais apenas para os lançamentos com baixa
        $totalPagamentos = $pagamentosComBaixa->sum('valor'); // Soma os valores dos pagamentos com baixa
        $totalRecebimentos = $recebimentosComBaixa->sum('valor'); // Soma os valores dos recebimentos com baixa
        $totalGeral = $totalRecebimentos - $totalPagamentos;

        // Formatar os dados para DataTables
        $data = [
            'pagamentos' => $pagamentos->map(function ($lancamento) {
                // Status default
                $status = 'pendente';

                // Verifica se a data de vencimento é igual à data atual (ou seja, está vencendo hoje)
                $vencido = Carbon::parse($lancamento->data_venc)->lt(Carbon::now());
                $hoje = Carbon::parse($lancamento->data_venc)->isToday(); // Verifica se a data é hoje
    
                // Se a data de vencimento for igual a hoje ou se não tiver status (não pago ainda), marca como 'warning' (pendente)
                if ($lancamento->isPago()) {
                    $status = 'recebido'; // Se já tiver sido recebido, marca como 'recebido'
                } elseif ($hoje) {
                    $status = 'warning'; // 'warning' para mostrar que está vencendo hoje
                } elseif ($vencido) {
                    $status = 'vencido'; // Caso esteja vencido e não pago, marca como 'vencido'
                }

                return [
                    'id' => $lancamento->id,
                    'descricao' => $lancamento->descricao,
                    'valor' => number_format($lancamento->valor, 2, ',', '.'),
                    'data_venc' => $lancamento->data_venc->format('d/m/Y'),
                    'status' => $status, // Agora o status pode ser 'vencido', 'pago', 'pendente' ou 'warning'
                ];
            }),
            'recebimentos' => $recebimentos->map(function ($lancamento) {
                // Status default
                $status = 'pendente';

                // Verifica se a data de vencimento é igual à data atual (ou seja, está vencendo hoje)
                $vencido = Carbon::parse($lancamento->data_venc)->lt(Carbon::now());
                $hoje = Carbon::parse($lancamento->data_venc)->isToday(); // Verifica se a data é hoje
    
                // Se a data de vencimento for igual a hoje ou se não tiver status (não pago ainda), marca como 'warning' (pendente)
                if ($lancamento->isPago()) {
                    $status = 'recebido'; // Se já tiver sido recebido, marca como 'recebido'
                } elseif ($hoje) {
                    $status = 'warning'; // 'warning' para mostrar que está vencendo hoje
                } elseif ($vencido) {
                    $status = 'vencido'; // Caso esteja vencido e não pago, marca como 'vencido'
                }
                return [
                    'id' => $lancamento->id,
                    'descricao' => $lancamento->descricao,
                    'valor' => number_format($lancamento->valor, 2, ',', '.'),
                    'data_venc' => $lancamento->data_venc->format('d/m/Y'),
                    'status' => $status, // Agora o status pode ser 'vencido', 'recebido', 'pendente' ou 'warning'
                ];
            }),
            // Totais
            'totais' => [
                'pagamentos' => number_format($totalPagamentos, 2, ',', '.'),
                'recebimentos' => number_format($totalRecebimentos, 2, ',', '.'),
                'geral' => number_format($totalGeral, 2, ',', '.'),
            ]
        ];

        return response()->json($data);
    }
}
