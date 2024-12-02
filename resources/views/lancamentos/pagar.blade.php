@extends('_theme')

@section('css')
<link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}" />
@endsection

@section('content')
<div class="container mt-5">
    <h2 class="text-center">Baixar Lançamento</h2>

    <!-- Formulário para Baixar Lançamento -->
    <form action="" method="POST" enctype="multipart/form-data">
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
                    <label class="form-check-label" for="aplicar_multa">Aplicar Multa:</label>
                    <input type="text" class="form-control mt-2" id="multa" name="multa" placeholder="Multa (%)"
                        value="{{$multa}}">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="aplicar_juros" name="aplicar_juros" checked>
                    <label class="form-check-label" for="aplicar_juros">Aplicar Juros:</label>
                    <input type="text" class="form-control mt-2" id="juros" name="juros" placeholder="Juros (%)"
                        value="{{$juros}}">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="aplicar_desconto" name="aplicar_desconto"
                        checked>
                    <label class="form-check-label" for="aplicar_desconto">Aplicar Desconto:</label>
                    <input type="text" class="form-control mt-2" id="desconto" name="desconto"
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
                var juros = parseFloat($('#juros').val()) || 0;
                valorFinal += (valorOriginal * (juros / 100));
            }

            // Verificar e calcular multa
            if ($('#aplicar_multa').is(':checked')) {
                var multa = parseFloat($('#multa').val()) || 0;
                valorFinal += (valorOriginal * (multa / 100));
            }

            // Verificar e calcular desconto
            if ($('#aplicar_desconto').is(':checked')) {
                var desconto = parseFloat($('#desconto').val()) || 0;
                valorFinal -= (valorOriginal * (desconto / 100));
            }

            // Atualizar o valor final a pagar no campo
            $('#valor_pago').val(valorFinal.toFixed(2));
        }

        // Inicializar o cálculo ao carregar a página
        // calcularTotal();

        $('#valor_a_pagar').mask('000.000.000.000.000,00', { reverse: true });
    });
</script>
@endsection