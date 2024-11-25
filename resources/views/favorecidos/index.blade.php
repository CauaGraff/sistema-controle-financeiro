@extends('_theme')

@section('title', 'Favorecidos')
@section("css")
<link rel="stylesheet" href="{{asset("css/dataTables.css")}}" />
<link rel="stylesheet" href="{{asset("css/toastr.min.css")}}" />
@endsection
@section('content')
<div class="container mt-4">
    <h1>Fornecedor/Clientes</h1>
    <a href="{{ route('favorecidos.create') }}" class="btn btn-primary mb-3">Cadastrar Novo Fornecedor/Clientes</a>
    <div class="table-responsive">

        <table id="favorecidosTable" class="table ">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>CNPJ/CPF</th>
                    <th>Tipo</th>
                    <th>Cidade</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($favorecidos as $favorecido)
                    <tr>
                        <td>{{ $favorecido->id }}</td>
                        <td>{{ $favorecido->nome }}</td>
                        <td>{{ $favorecido->formatarDocumento() }}</td>
                        <td>{{ $favorecido->tipo == 'F' ? 'Fornecedor' : 'Cliente' }}</td>
                        <td>{{ $favorecido->cidade }}</td>
                        <td>
                            <a href="{{ route('favorecidos.edit', $favorecido->id) }}"
                                class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('favorecidos.destroy', $favorecido->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
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