<?php

namespace App\Http\Controllers;

use ZipArchive;
use Carbon\Carbon;
use App\Models\Empresas;
use App\Models\Lancamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ExportacaoController extends Controller
{
    public function exportarLancamentos(Request $request, $idEmpresa)
    {
        $competencia = $request->input('competencia');
        $competenciaFormatada = str_replace('/', '_', $competencia); // Substitui "/" por "_"
        $dataInicio = Carbon::createFromFormat('m/Y', $competencia)->startOfMonth();
        $dataFim = $dataInicio->copy()->endOfMonth();

        $empresa = Empresas::findOrFail($idEmpresa);

        // Busca os lançamentos da competência pela data_baixa
        $lancamentos = Lancamento::with(['categoriaContas', 'fornecedorCliente', 'lancamentoBaixa', 'conta'])
            ->where('id_empresa', $idEmpresa)
            ->whereHas('lancamentoBaixa', function ($query) use ($dataInicio, $dataFim) {
                $query->whereBetween('data_baixa', [$dataInicio, $dataFim]); // Filtra pela data_baixa
            })
            ->get();

        $csvData = [];
        $csvData[] = [
            'Data da Baixa',
            'Número do Documento',
            'Débito',
            'Crédito',
            'Valor',
            'Histórico',
            'Descrição Completa',
            'Juros',      // Nova coluna para Juros
            'Multa',      // Nova coluna para Multa
            'Desconto'    // Nova coluna para Desconto
        ];

        foreach ($lancamentos as $lancamento) {
            $lancamentoBaixa = $lancamento->lancamentoBaixa;

            // Define o histórico com base no tipo
            $historico = $lancamento->tipo === 'P' ? 878 : 1;

            // Define débito e crédito com base no tipo
            $debito = $lancamento->tipo === 'P'
                ? $this->formatarCnpjCpf($lancamento->fornecedorCliente->cnpj_cpf ?? '') // Formata o CNPJ ou CPF
                : (($lancamento->lancamentoBaixa->contaBancaria->nome ?? '---') . '-' .
                    ($lancamento->lancamentoBaixa->contaBancaria->agencia ?? '---') . ' / ' .
                    ($lancamento->lancamentoBaixa->contaBancaria->conta ?? '---')
                );

            $credito = $lancamento->tipo === 'R'
                ? $this->formatarCnpjCpf($lancamento->fornecedorCliente->cnpj_cpf ?? '') // Formata o CNPJ ou CPF
                : (($lancamento->lancamentoBaixa->contaBancaria->nome ?? '---') . '-' .
                    ($lancamento->lancamentoBaixa->contaBancaria->agencia ?? '---') . ' / ' .
                    ($lancamento->lancamentoBaixa->contaBancaria->conta ?? '---')
                );

            // Valores de Juros, Multa e Desconto
            $juros = $lancamentoBaixa->juros ?? 0;
            $multa = $lancamentoBaixa->multa ?? 0;
            $desconto = $lancamentoBaixa->desconto ?? 0;

            $csvData[] = [
                Carbon::parse($lancamentoBaixa->data_baixa)->format('d/m/Y'), // Converte data_baixa para Carbon antes de formatar
                $lancamentoBaixa->doc ?? '',
                $debito,
                $credito,
                number_format($lancamentoBaixa->valor, 2, ',', '.'),
                $historico,
                ($lancamentoBaixa->doc ?? '') . ' - ' . $lancamento->descricao . ' - ' . ($lancamento->fornecedorCliente->nome ?? '') . ' - ' . ($lancamento->categoriaContas->descricao ?? ''),
                number_format($juros, 2, ',', '.'),
                number_format($multa, 2, ',', '.'),
                number_format($desconto, 2, ',', '.')
            ];
        }

        // Cria um arquivo temporário para o CSV
        $csvHandle = fopen('php://temp', 'r+');

        // Escreve os dados no CSV
        foreach ($csvData as $linha) {
            fputcsv($csvHandle, $linha, ';');
        }

        // Move o ponteiro para o início do arquivo
        rewind($csvHandle);

        // Cria um arquivo ZIP em memória
        $zip = new \ZipArchive();
        $zipFileName = "exportacao_{$competenciaFormatada}.zip";
        $zipPath = tempnam(sys_get_temp_dir(), 'zip');

        if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
            // Adiciona o CSV ao ZIP
            $csvContent = stream_get_contents($csvHandle);
            $zip->addFromString("exportacao_{$competenciaFormatada}.csv", $csvContent);

            // Adiciona anexos ao ZIP
            foreach ($lancamentos as $lancamento) {
                if ($lancamento->lancamentoBaixa && $lancamento->lancamentoBaixa->anexo) {
                    $anexoPath = storage_path("app/public/{$lancamento->lancamentoBaixa->anexo}");
                    if (file_exists($anexoPath)) {
                        $zip->addFile($anexoPath, "{$lancamento->lancamentoBaixa->anexo}");
                    }
                }
            }
            $zip->close();
        }

        // Fecha o CSV handle
        fclose($csvHandle);

        // Retorna o ZIP para download e remove o arquivo temporário após o envio
        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }

    /**
     * Formata um CNPJ ou CPF.
     *
     * @param string|null $documento
     * @return string
     */
    private function formatarCnpjCpf(?string $documento): string
    {
        if (!$documento) {
            return '---'; // Retorna um valor padrão caso o documento seja nulo
        }

        $documento = preg_replace('/\D/', '', $documento); // Remove caracteres não numéricos

        if (strlen($documento) === 11) {
            // Formata como CPF: 000.000.000-00
            return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $documento);
        } elseif (strlen($documento) === 14) {
            // Formata como CNPJ: 00.000.000/0000-00
            return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $documento);
        }

        return $documento; // Retorna o documento sem formatação se não for CPF nem CNPJ
    }
}
