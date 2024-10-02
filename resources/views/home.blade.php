@extends('._theme')

@section("title", "Página Inicial")

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">Dados da Empresa</div>
        <div class="card-body" id="company-details">
            <p><strong>Total de Pagamentos:</strong> R$ <span id="total-pagamentos"></span></p>
            <p><strong>Total de Recebimentos:</strong> R$ <span id="total-recebimentos"></span></p>
            <p><strong>Total Geral:</strong> R$ <span id="total-geral"></span></p>
        </div>
    </div>
</div>
@endsection


@section(section: 'js')
<script>
    $(document).ready(function() {
        // Selecione os links da empresa no dropdown
        $('.select-company').on('click', function(e) {
            e.preventDefault();

            var companyId = $(this).data('company-id');

            // Faça a requisição AJAX usando jQuery
            $.ajax({
                url: '/empresa/' + companyId + '/detalhes',
                method: 'GET',
                success: function(data) {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        // Atualize os dados no card usando jQuery
                        $('#total-pagamentos').text(data.total_pagamentos.toFixed(2));
                        $('#total-recebimentos').text(data.total_recebimentos.toFixed(2));
                        $('#total-geral').text(data.total_geral.toFixed(2));
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao buscar os dados da empresa:', error);
                }
            });
        });
    });
</script>
@endsection