@extends('_theme')

@section('css')
<link rel="stylesheet" href="{{asset('css/toastr.min.css')}}" />
@endsection

@section('content')
<div class="container mt-5">
    <h2 class="text-center">Baixar Lançamento</h2>

    <!-- Formulário para Baixar Lançamento -->
    <form action="" method="POST">
        @csrf

        <div class="form-group">
            <label for="descricao">Descrição:</label>
            <input type="text" class="form-control" id="descricao" value="{{ $lancamento->descricao }}" disabled>
        </div>

        <div class="form-group">
            <label for="valor_a_pagar">Valor a Pagar:</label>
            <input type="text" class="form-control" id="valor_a_pagar"
                value="R$ {{ number_format($lancamento->valor, 2, ',', '.') }}" disabled>
        </div>

        <div class="form-group">
            <label for="data_venc">Data de Vencimento:</label>
            <input type="text" class="form-control" id="data_venc" value="{{$lancamento->data_venc}}" disabled>
        </div>

        <div class="form-group">
            <label for="data_pagamento">Data do Pagamento:</label>
            <input type="date" class="form-control" name="data_pagamento" id="data_pagamento" required>
        </div>

        <!-- Checkboxes para juros, multa, desconto -->
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="aplicar_juros" name="aplicar_juros" checked>
            <label class="form-check-label" for="aplicar_juros">Aplicar Juros:</label>
            <input type="number" class="form-control mt-2" id="juros" name="juros" placeholder="Juros (%)" value="0"
                step="0.01" min="0">
        </div>

        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="aplicar_multa" name="aplicar_multa" checked>
            <label class="form-check-label" for="aplicar_multa">Aplicar Multa:</label>
            <input type="number" class="form-control mt-2" id="multa" name="multa" placeholder="Multa (%)" value="0"
                step="0.01" min="0">
        </div>

        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="aplicar_desconto" name="aplicar_desconto" checked>
            <label class="form-check-label" for="aplicar_desconto">Aplicar Desconto:</label>
            <input type="number" class="form-control mt-2" id="desconto" name="desconto" placeholder="Desconto (%)"
                value="0" step="0.01" min="0">
        </div>

        <!-- Campo para o número do documento (NF, CTE) -->
        <div class="form-group mt-3">
            <label for="numero_documento">Número do Documento:</label>
            <input type="text" class="form-control" id="numero_documento" name="numero_documento"
                placeholder="Número da NF ou CTE">
        </div>

        <div class="form-group mt-3">
            <button type="submit" class="btn btn-success btn-lg w-100">Confirmar Pagamento</button>
        </div>
    </form>
</div>
@endsection

@section('js')
<script src="{{asset('js/toastr.min.js')}}"></script>
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
            $('#valor_a_pagar').val('R$ ' + valorFinal.toFixed(2).replace('.', ','));
        }

        // Inicializar o cálculo ao carregar a página
        calcularTotal();
    });
</script>
@endsection