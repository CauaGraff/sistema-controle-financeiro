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

</script>
@endsection