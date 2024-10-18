@extends('_theme')

@section('title', 'Categorias de Contas')
@section("css")
<link rel="stylesheet" href="{{asset("css/dataTables.css")}}" />
<link rel="stylesheet" href="{{asset("css/toastr.min.css")}}" />
@endsection
@section('content')
<div class="container mt-4">
    <h1>Categorias de Contas</h1>
    <a href="{{ route('categorias.create') }}" class="btn btn-primary mb-3">Cadastrar Nova Categoria</a>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Descrição</th>
                <th>Ações</th>
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
                    <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
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
                    <a href="{{ route('categorias.edit', $subcategoria->id) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('categorias.destroy', $subcategoria->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
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
<script>
    $(".table").DataTable({
        language: {
            url: '{{asset("js/json/data_Table_pt_br.json")}}',
        },
        order: []

    });
</script>
@endsection