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
            <!-- Categoria Pai -->
            <tr>
                <td><strong>{{ $numeroCategoriaPai }}</strong></td>
                <td><strong>{{ $categoria->descricao }}</strong></td>
                <td>
                    <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn btn-warning btn-sm"><i
                            class="fa-solid fa-pen-to-square" style="color: white;"></i></a>
                    <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>

            <!-- Subcategorias -->
            @if ($categoria->subcategorias->count() > 0)
            @php
            $numeroSubcategoria = 1; // Inicializa a numeração das subcategorias
            @endphp

            @foreach ($categoria->subcategorias as $subcategoria)
            <tr>
                <td>{{ $numeroCategoriaPai }}.{{ $numeroSubcategoria }}</td>
                <td>-- {{ $subcategoria->descricao }}</td>
                <td>
                    <a href="{{ route('categorias.edit', $subcategoria->id) }}" class="btn btn-warning btn-sm"><i
                            class="fa-solid fa-pen-to-square" style="color: white;"></i></a>
                    <form action="{{ route('categorias.destroy', $subcategoria->id) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @php
            $numeroSubcategoria++; // Incrementa o número da subcategoria
            @endphp
            @endforeach
            @endif

            @php
            $numeroCategoriaPai++; // Incrementa o número da categoria pai
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