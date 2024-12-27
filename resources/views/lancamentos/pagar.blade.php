@extends('_theme')

@section('css')
<link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}" />
@endsection

@section('content')
<div class="container mt-5">
    <h2 class="text-center">Baixar Lançamento</h2>
    <!-- Formulário para Baixar Lançamento -->
    <form
        action="{{$lancamento->tipo = 'P' ? route('lancamentos.pagamentos.baixa.store', $lancamento->id) : route('lancamentos.recebimento.baixa.store', $lancamento->id)}}"
        method="POST" enctype="multipart/form-data">
        @csrf
        <!-- Linha para Descrição e Valor a Pagar -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="descricao">Descrição:</label>
                    <input type="text" class="form-control" id="descricao" value="{{ $lancamento->descricao }}"
                        disabled>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="valor_a_pagar">Valor a Pagar:</label>
                    <input type="text" class="form-control" id="valor_a_pagar" value="{{$lancamento->valor}}" disabled>
                </div>
            </div>
        </div>
        <!-- Linha para Data de Vencimento e Data do Pagamento -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="data_venc">Data de Vencimento:</label>
                    <input type="date" class="form-control" id="data_venc" value="{{$lancamento->data_venc}}" disabled>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="data_pagamento">Data do Pagamento:</label>
                    <input type="date" class="form-control" name="data_pagamento" id="data_pagamento"
                        value="{{$today = (new DateTime())->format('Y-m-d')}}" required>
                </div>
            </div>
        </div>
        <!-- Linha para Juros, Multa e Desconto -->
        <div class="row mt-2">
            <div class="col-md-2">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="aplicar_multa" name="aplicar_multa" checked>
                    <label class="form-check-label" for="aplicar_multa">Multa:</label>
                    <input type="text" class="form-control mt-2" id="multa" name="multa" placeholder="Multa (R$)"
                        value="{{$multa}}" readonly>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="aplicar_juros" name="aplicar_juros" checked>
                    <label class="form-check-label" for="aplicar_juros">Juros:</label>
                    <input type="text" class="form-control mt-2" id="juros" name="juros" placeholder="Juros (R$)"
                        value="{{$juros}}" readonly>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="aplicar_desconto" name="aplicar_desconto"
                        checked>
                    <label class="form-check-label" for="aplicar_desconto">Desconto:</label>
                    <input type="text" class="form-control mt-2" id="desconto" name="desconto"
                        placeholder="Desconto (R$)" value="{{$desconto}}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-check-label" for="valor_pago">Valor Pago:</label>
                    <input type="text" class="form-control mt-2" id="valor_pago" name="valor_pago"
                        placeholder="Valor Pago" value="{{$valor_total}}">
                </div>
            </div>
        </div>

        <!-- Linha para o número do documento -->
        <div class="row mt-2">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="numero_documento">Número do Documento:</label>
                    <input type="text" class="form-control" id="numero_documento" name="numero_documento">
                </div>
            </div>
            <div class="col-md-9">
                <div class="form-group">
                    <label for="anexo">Anexar Documento:</label>
                    <input type="file" class="form-control" id="anexo" name="anexo" accept="application/pdf,image/*">
                </div>
            </div>
        </div>
        <!-- Botão de Submissão -->
        <div class="form-group mt-3">
            <button type="submit" class="btn btn-success btn-lg w-100">Confirmar Pagamento</button>
        </div>
    </form>
</div>
@endsection

@section('js')
<script src="{{ asset('js/toastr.min.js') }}"></script>
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#multa, #juros, #desconto').css({
            "background-color": "var(--bs-secondary-bg)",
        });
        var valorOriginal = parseFloat($('#valor_a_pagar').val().replace('.', '').replace(',', '.')); // Remover máscara e converter para número
        // Aplica a máscara de moeda nos campos
        $('#valor_a_pagar, #multa, #juros, #desconto, #valor_pago').mask('#.##0,00', { reverse: true });
        // Eventos separados para cada checkbox
        $('#aplicar_multa').change(function () {
            if ($(this).prop('checked')) {
                $('#multa').prop('readonly', true).css({
                    "background-color": "var(--bs-secondary-bg)",
                }); // Desabilita o input de multa
            } else {
                $('#multa').prop('readonly', false).css({
                    "background-color": "var(--bs-body-bg)",
                }); // Habilita o input de multa
            }
        });
        $('#aplicar_juros').change(function () {
            if ($(this).prop('checked')) {
                $('#juros').prop('readonly', true).css({
                    "background-color": "var(--bs-secondary-bg)",
                }); // Desabilita o input de multa
            } else {
                $('#juros').prop('readonly', false).css({
                    "background-color": "var(--bs-body-bg)",
                }); // Habilita o input de multa
            }
        });
        $('#aplicar_desconto').change(function () {
            if ($(this).prop('checked')) {
                $('#desconto').prop('readonly', true).css({
                    "background-color": "var(--bs-secondary-bg)",
                }); // Desabilita o input de multa
            } else {
                $('#desconto').prop('readonly', false).css({
                    "background-color": "var(--bs-body-bg)",
                }); // Habilita o input de multa
            }
        });
        $('#multa, #juros, #desconto').change(function () {
            recalcularValores();
        });
        // Atualiza o valor pago ao alterar o campo "Valor Pago"
        $('#valor_pago').on('change', function () {
            $('#multa').val("0,00");
            $('#juros').val("0,00");
            $('#desconto').val("0,00");
            // Recalcula os valores com base nos inputs
            var valorOriginal = parseFloat($('#valor_a_pagar').val().replace('.', '').replace(',', '.')); // Valor original
            var valorPago = parseFloat($(this).val().replace('.', '').replace(',', '.'));
            // Calcula a diferença entre o valor pago e o valor final
            var diferenca = valorPago - valorOriginal;
            // Se o valor pago for maior que o valor final, ajusta o desconto
            if (diferenca < 0) {
                // Se o campo de desconto estiver marcado, ajusta o valor do desconto com a diferença
                $('#desconto').val(Math.abs(diferenca).toFixed(2).replace('.', ','));
            }
            // Se o valor pago for menor que o valor final, ajusta os juros
            else if (diferenca > 0) {
                // Se o campo de juros estiver marcado, ajusta o valor dos juros com a diferença
                $('#juros').val(diferenca.toFixed(2).replace('.', ','));
            }
            // Atualiza o campo "valor_pago" com a máscara de moeda
            $(this).val(valorPago.toFixed(2).replace('.', ','));
        });
        function recalcularValores() {
            // Recalcula os valores com base nos inputs
            var valorOriginal = parseFloat($('#valor_a_pagar').val().replace('.', '').replace(',', '.')); // Valor original
            var multa = parseFloat($('#multa').val().replace('.', '').replace(',', '.')) || 0; // Valor da multa
            var juros = parseFloat($('#juros').val().replace('.', '').replace(',', '.')) || 0; // Valor dos juros
            var desconto = parseFloat($('#desconto').val().replace('.', '').replace(',', '.')) || 0; // Valor do desconto
            var valorFinal = valorOriginal + multa + juros - desconto; // Valor final considerando multa, juros e desconto
            // Atualiza o campo de "Valor Pago"
            $('#valor_pago').val(valorFinal.toFixed(2).replace('.', ','));
            // Verifica o valor pago e ajusta a diferença de juros ou desconto
            var valorPago = parseFloat($('#valor_pago').val().replace('.', '').replace(',', '.'));
            var diferenca = valorPago - valorFinal;
            if (diferenca > 0) {
                // Se o valor pago for maior que o valor final, é provável que o desconto deva ser ajustado
                if ($('#aplicar_desconto').prop('checked')) {
                    $('#desconto').val(diferenca.toFixed(2).replace('.', ','));
                }
            } else if (diferenca < 0) {
                // Se o valor pago for menor que o valor final, é provável que o juros deva ser ajustado
                if ($('#aplicar_juros').prop('checked')) {
                    $('#juros').val(Math.abs(diferenca).toFixed(2).replace('.', ',')); // Ajusta os juros com a diferença
                }
            }
        }
    });
</script>
@endsection