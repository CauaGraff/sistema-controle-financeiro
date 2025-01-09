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
        $lancamentos = Lancamento::whereBetween('data_venc', [$startOfMonth, $endOfMonth])->where('id_empresa', session('empresa_id'))->get();

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
        $month = $request->get('month');
        $year = $request->get('year');

        // Se não for passado mês ou ano, usa o mês atual
        $month = $month ?: Carbon::now()->month;
        $year = $year ?: Carbon::now()->year;

        // Obtém o primeiro dia do mês e o último dia do mês, mas sem a hora
        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth()->endOfDay();

        // Recupera os lançamentos de pagamentos
        $pagamentos = Lancamento::whereBetween('data_venc', [$startOfMonth, $endOfMonth])
            ->where('id_empresa', session('empresa_id'))
            ->where('tipo', 'P')
            ->get();

        // Recupera os lançamentos de recebimentos
        $recebimentos = Lancamento::whereBetween('data_venc', [$startOfMonth, $endOfMonth])
            ->where('id_empresa', session('empresa_id'))
            ->where('tipo', 'R')
            ->get();

        // Formatar os dados para DataTables
        $data = [
            'pagamentos' => $pagamentos->map(function ($lancamento) {
                return [
                    'id' => $lancamento->id,
                    'descricao' => $lancamento->descricao,
                    'valor' => "R$ " . number_format($lancamento->valor, 2, ',', '.'),
                    'data_venc' => $lancamento->data_venc->format('d/m/Y'),
                    'status' => $lancamento->isPago() ? 'Pago' : 'Pendente',
                ];
            }),
            'recebimentos' => $recebimentos->map(function ($lancamento) {
                return [
                    'id' => $lancamento->id,
                    'descricao' => $lancamento->descricao,
                    'valor' => number_format($lancamento->valor, 2, ',', '.'),
                    'data_venc' => $lancamento->data_venc->format('d/m/Y'),
                    'status' => $lancamento->isPago() ? 'Recebido' : 'Pendente',
                ];
            }),
        ];

        return response()->json($data);
    }
}
