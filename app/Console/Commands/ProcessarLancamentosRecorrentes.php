<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Lancamento;
use App\Models\LancamentoRecorrenciaConfig;
use Carbon\Carbon;

class ProcessarLancamentosRecorrentes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lancamentos:processar-recorrentes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processa lançamentos recorrentes gerando os próximos lançamentos.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Data de hoje para evitar geração de lançamentos no futuro.
        $hoje = Carbon::today();

        // Buscar todos os lançamentos recorrentes ativos
        $configs = LancamentoRecorrenciaConfig::where('ativo', true)->get();

        foreach ($configs as $config) {
            // Verificar o último lançamento gerado para esta recorrência
            $ultimoLancamento = Lancamento::where('descricao', $config->descricao)
                ->where('id_categoria', $config->id_categoria)
                ->where('id_empresa', $config->id_empresa)
                ->orderBy('data_venc', 'desc')
                ->first();

            // Definir a data de início (após o último lançamento, ou a data de início da recorrência)
            $dataInicio = $ultimoLancamento
                ? Carbon::parse($ultimoLancamento->data_venc)->copy()
                : Carbon::parse($config->data_inicio);

            // Definir a data de limite para a geração de lançamentos, 3 meses após a data de início, ou até data_fim, se existir.
            if (!$config->data_fim) {
                // Limitar a 3 meses caso a data_fim seja nula
                $dataLimite = $dataInicio->copy()->addMonths(3); // Limite de 3 meses
            } else {
                $dataLimite = Carbon::parse($config->data_fim);
            }

            // Garantir que a data limite não seja no futuro
            $dataLimite = $dataLimite->min(Carbon::today()->addMonths(3)); // Definir 3 meses como limite máximo se data_fim não for fornecido.

            // Se não há data final, vamos gerar os próximos lançamentos dentro do limite de 3 meses.
            DB::transaction(function () use ($config, &$dataInicio, $dataLimite) {
                $contadorLancamentos = 0;

                // Gerar os próximos lançamentos, no máximo 3 vezes
                while ($contadorLancamentos < 3) {
                    // Verificar se ultrapassou o limite de 3 meses
                    if ($dataInicio->greaterThan($dataLimite)) {
                        break;
                    }

                    // Verificar se o lançamento já existe para evitar duplicações
                    $lancamentoExistente = Lancamento::where('descricao', $config->descricao)
                        ->where('id_categoria', $config->id_categoria)
                        ->where('id_empresa', $config->id_empresa)
                        ->whereDate('data_venc', $dataInicio)
                        ->exists();

                    if (!$lancamentoExistente) {
                        // Criar o lançamento
                        Lancamento::create([
                            'descricao' => $config->descricao,
                            'valor' => $config->valor,
                            'tipo' => $config->tipo,
                            'data_venc' => $dataInicio,
                            'id_categoria' => $config->id_categoria,
                            'id_empresa' => $config->id_empresa,
                            'id_fornecedor_cliente' => $config->id_fornecedor_cliente,
                        ]);
                        $contadorLancamentos++;
                    }

                    // Avançar para o próximo intervalo de recorrência
                    $dataInicio = $this->addIntervalo($dataInicio, $config->tipo_recorrencia);
                }

                $this->info('Lançamentos recorrentes processados com sucesso!');
            });
        }

        return Command::SUCCESS;
    }

    /**
     * Adiciona o intervalo de tempo ao Carbon de acordo com o tipo de recorrência.
     *
     * @param Carbon $data
     * @param string $tipoRecorrencia
     * @return Carbon
     */
    private function addIntervalo(Carbon $data, string $tipoRecorrencia): Carbon
    {
        switch ($tipoRecorrencia) {
            case 'diaria':
                return $data->addDay();
            case 'semanal':
                return $data->addWeek();
            case 'mensal':
                return $data->addMonth();
            case 'anual':
                return $data->addYear();
            default:
                throw new \InvalidArgumentException('Tipo de recorrência inválido.');
        }
    }
}
