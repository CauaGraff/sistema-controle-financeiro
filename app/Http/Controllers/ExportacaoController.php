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
            'Descrição Completa'
        ];

        foreach ($lancamentos as $lancamento) {
            $lancamentoBaixa = $lancamento->lancamentoBaixa;

            // Define o histórico com base no tipo
            $historico = $lancamento->tipo === 'P' ? 878 : 1;

            // Define débito e crédito com base no tipo
            $debito = $lancamento->tipo === 'P'
                ? ($lancamento->fornecedorCliente->cnpj_cpf ?? '---') // CNPJ do fornecedor no débito
                : ($lancamento->conta->conta_bancaria ?? 'Conta bancária não definida'); // Conta bancária no débito para recebimento

            $credito = $lancamento->tipo === 'R'
                ? ($lancamento->fornecedorCliente->cnpj_cpf ?? '---') // CNPJ do cliente no crédito
                : ($lancamento->conta->conta_bancaria ?? 'Conta bancária não definida'); // Conta bancária no crédito para pagamento

            $csvData[] = [
                $lancamentoBaixa->updated_at->format('d/m/Y'),
                $lancamentoBaixa->doc ?? '---',
                $debito,
                $credito,
                number_format($lancamentoBaixa->valor, 2, ',', '.'),
                $historico,
                $lancamento->descricao
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
                    $anexoPath = storage_path("app/public/anexos/{$lancamento->lancamentoBaixa->anexo}");
                    if (file_exists($anexoPath)) {
                        $zip->addFile($anexoPath, "anexos/{$lancamento->lancamentoBaixa->anexo}");
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

