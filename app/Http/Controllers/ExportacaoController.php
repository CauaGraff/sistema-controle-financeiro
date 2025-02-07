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

        // Busca os lançamentos da competência
        $lancamentos = Lancamento::with(['categoriaContas', 'fornecedorCliente', 'lancamentoBaixa', 'conta'])
            ->where('id_empresa', $idEmpresa)
            ->whereBetween('data_venc', [$dataInicio, $dataFim])
            ->whereHas('lancamentoBaixa') // Apenas lançamentos com baixa
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
                ? ($lancamento->fornecedorCliente->cnpj_cpf ?? '---') // CNPJ do fornecedor no débito
                : (($lancamento->lancamentoBaixa->contaBancaria->nome ?? '---') . '-' .
                    ($lancamento->lancamentoBaixa->contaBancaria->agencia ?? '---') . ' / ' .
                    ($lancamento->lancamentoBaixa->contaBancaria->conta ?? '---')
                ); // Conta bancária no débito para recebimento

            $credito = $lancamento->tipo === 'R'
                ? ($lancamento->fornecedorCliente->cnpj_cpf ?? '---') // CNPJ do cliente no crédito
                : (($lancamento->lancamentoBaixa->contaBancaria->nome ?? '---') . '-' .
                    ($lancamento->lancamentoBaixa->contaBancaria->agencia ?? '---') . ' / ' .
                    ($lancamento->lancamentoBaixa->contaBancaria->conta ?? '---')
                ); // Conta bancária no crédito para pagamento

            // Valores de Juros, Multa e Desconto
            $juros = $lancamentoBaixa->juros ?? 0;  // Exemplo de como você pode pegar ou calcular o valor de juros
            $multa = $lancamentoBaixa->multa ?? 0;  // Exemplo de como você pode pegar ou calcular o valor de multa
            $desconto = $lancamentoBaixa->desconto ?? 0;  // Exemplo de como você pode pegar ou calcular o valor de desconto

            $csvData[] = [
                $lancamentoBaixa->updated_at->format('d/m/Y'),
                $lancamentoBaixa->doc ?? '---',
                $debito,
                $credito,
                number_format($lancamentoBaixa->valor, 2, ',', '.'),
                $historico,
                ($lancamentoBaixa->doc ?? '---') . ' - ' . $lancamento->descricao . ' - ' . ($lancamento->fornecedorCliente->nome ?? '---') . ' - ' . ($lancamento->categoriaContas->descricao ?? '---'),
                number_format($juros, 2, ',', '.'),    // Formatação de juros
                number_format($multa, 2, ',', '.'),    // Formatação de multa
                number_format($desconto, 2, ',', '.')  // Formatação de desconto
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

}

