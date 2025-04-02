@extends('_theme')

@section('title', 'Categorias de Contas')
@section("css")
<link rel="stylesheet" href="{{asset("css/dataTables.css")}}" />
<link rel="stylesheet" href="{{asset("css/toastr.min.css")}}" />
<style>
    /* Ensure that the demo table scrolls */
    th,
    td {
        white-space: nowrap;
    }

    div.dataTables_wrapper {
        width: 800px;
        margin: 0 auto;
    }
</style>
@endsection
@section('content')
<div class="container mt-4">
    <h1>Categorias de Contas</h1>
    <div class="d-flex justify-content-end align-items-center mb-3">
        <a href="{{ route('categorias.create') }}" class="btn btn-primary mb-3">Cadastrar Nova Categoria</a>
    </div>
    <table class="table table-striped stripe row-border order-column" style="width:100%">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Descrição</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
    @php
        $numeroCategoriaPai = 1; // Inicializa a numeração das categorias pai
    @endphp

    @foreach ($categorias as $categoria)
        @include('categorias._categoria', ['categoria' => $categoria, 'numero' => $numeroCategoriaPai, 'nivel' => 0])
        @php
            $numeroCategoriaPai++;
        @endphp
    @endforeach
</tbody>
    </table>
</div>
@endsection

@section('js')
<script src="{{asset("js/dataTables.js")}}"></script>
<script src="{{asset("js/toastr.min.js")}}"></script>
<script src="{{asset("js/dataTables.fixedColumns.js")}}"></script>
<script src="{{asset("js/fixedColumns.dataTables.js")}}"></script>
<script>
    $(document).ready(function() {
        var table = $(".table").DataTable({
            order: {},
            language: {
                url: '{{asset("js/json/data_Table_pt_br.json")}}'
            },
            fixedColumns: {
                start: 0,
                end: 1
            },
            scrollX: true
        });
    })
</script>
@endsection