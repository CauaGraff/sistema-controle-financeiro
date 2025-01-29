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

        // Define o diretório e cria se não existir
        $storagePath = storage_path("app/public/exportacao");
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0777, true);
        }

        // Gera o CSV
        $fileName = "exportacao_{$competenciaFormatada}.csv";
        $filePath = "{$storagePath}/{$fileName}";

        $file = fopen($filePath, 'w');
        foreach ($csvData as $linha) {
            fputcsv($file, $linha, ';');
        }
        fclose($file);

        // Verifica anexos para criar um ZIP
        $zipFileName = "exportacao_{$competenciaFormatada}.zip";
        $zipFilePath = "{$storagePath}/{$zipFileName}";

        $zip = new ZipArchive;
        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            // Adiciona o CSV ao ZIP
            $zip->addFile($filePath, $fileName);

            // Adiciona anexos ao ZIP
            foreach ($lancamentos as $lancamento) {
                if ($lancamento->lancamentoBaixa && $lancamento->lancamentoBaixa->anexo) {
                    $anexoPath = storage_path("app/public/{$lancamento->lancamentoBaixa->anexo}");
                    if (file_exists($anexoPath)) {
                        $zip->addFile($anexoPath, "anexos/{$lancamento->lancamentoBaixa->anexo}");
                    }
                }
            }
            $zip->close();
        }
        // Retorna o arquivo ZIP
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }
}

