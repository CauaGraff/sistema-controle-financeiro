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
                    <input type="checkbox" class="form-check-input" id="aplicar_multa" name="aplicar_multa">
                    <label class="form-check-label" for="aplicar_multa">Multa:</label>
                    <input type="text" class="form-control mt-2" id="multa" name="multa" placeholder="Multa (%)"
                        value="{{$multa}}" disabled>
                    <input type="hidden" class="form-control mt-2" id="multa" name="multa" placeholder="Multa (%)"
                        value="{{$multa}}">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="aplicar_juros" name="aplicar_juros">
                    <label class="form-check-label" for="aplicar_juros">Juros:</label>
                    <input type="text" class="form-control mt-2" id="juros" name="juros" placeholder="Juros (%)"
                        value="{{$juros}}" disabled>
                    <input type="hidden" class="form-control mt-2" id="juros" name="juros" placeholder="Juros (%)"
                        value="{{$juros}}">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="aplicar_desconto" name="aplicar_desconto">
                    <label class="form-check-label" for="aplicar_desconto">Desconto:</label>
                    <input type="text" class="form-control mt-2" id="desconto" name="desconto"
                        placeholder="Desconto (%)" value="{{$desconto}}" disabled>
                    <input type="hidden" class="form-control mt-2" id="desconto" name="desconto"
                        placeholder="Desconto (%)" value="{{$desconto}}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-check-label" for="aplicar_desconto">Valor Pago:</label>
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
        $('#valor_a_pagar, #multa, #juros, #desconto, #valor_pago').mask('#.##0,00', { reverse: true });
        $('#valor_pago').change(function () {
            // Remove os pontos (separadores de milhar) e troca a vírgula por ponto para valores decimais
            var valorPago = parseFloat($("#valor_pago").val().replace(/\./g, '').replace(',', '.'));
            var valorOriginal = parseFloat("{{ $lancamento->valor }}");
            var novoTotal = valorPago - valorOriginal;
            console.log(valorPago); // Verifique no console o valor correto de valorPago
            $('#multa, #juros, #desconto').val(0);
            if (novoTotal > 0) {
                $('#juros').val(novoTotal.toFixed(2));
                $('#juros').mask('#.##0,00', { reverse: true });
            }
            if (novoTotal < 0) {
                $('#desconto').val(Math.abs(novoTotal.toFixed(2)));
                $('#desconto').mask('#.##0,00', { reverse: true });
            }
        });
        // Recalcular o valor total com base nos valores inseridos para juros, multa e desconto
        $('#aplicar_juros, #aplicar_multa, #aplicar_desconto').change(function () {
            calcularTotal();
        });
        // Calcular os valores totais com base nos juros, multa e desconto
        function calcularTotal() {
            var valorOriginal = parseFloat("{{ $lancamento->valor }}");
            var valorFinal = valorOriginal;

            // Verificar e calcular juros
            if ($('#aplicar_juros').is(':checked')) {
                $('#juros').prop('disabled', false)
            }
            if ($('#aplicar_desconto').is(':checked')) {
                $('#desconto').prop('disabled', false)
            }
            if ($('#aplicar_multa').is(':checked')) {
                $('#multa').prop('disabled', false)
            }
        }
        // Inicializar o cálculo ao carregar a página
        // calcularTotal();
    });
</script>
@endsection