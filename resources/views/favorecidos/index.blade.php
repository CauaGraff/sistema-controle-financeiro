@extends('_theme')

@section("css")
<link rel="stylesheet" href="{{ asset('css/dataTables.css') }}" />
@endsection

@section('content')
<div class="container">
    <h1>Fornecedores/Clientes</h1>
    <div class="d-flex justify-content-end align-items-center mb-3">
        <a href="{{ route('favorecidos.create') }}" class="btn btn-primary">Cadastrar Novo Fornecedor/Cliente</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped stripe row-border order-column" style="width:100%">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>CNPJ/CPF</th>
                    <th>Telefone</th>
                    <th>Email</th>
                    <th>Tipo</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($favorecidos as $fornecedorCliente)
                <tr>
                    <td>{{ $fornecedorCliente->id }}</td>
                    <td>{{ $fornecedorCliente->nome }}</td>
                    <td>{{ $fornecedorCliente->formatarDocumento() }}</td>
                    <td>{{ $fornecedorCliente->telefone }}</td>
                    <td>{{ $fornecedorCliente->email }}</td>
                    <td>@if ($fornecedorCliente->tipo == 'F') Fornecedor @else Cliente @endif</td>
                    <td class="text-center">
                        <a href="{{ route('favorecidos.edit', $fornecedorCliente->id) }}" class="btn btn-warning btn-sm"><i
                                class="fa-solid fa-pen-to-square" style="color: white;"></i></a>
                        <form action="{{ route('favorecidos.destroy', $fornecedorCliente->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Deseja realmente excluir este fornecedor/cliente?')"><i
                                    class="fa-solid fa-trash"></i></button>
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
<script src="{{ asset('js/dataTables.js') }}"></script>
<script>
    $(document).ready(function() {
        $(".table").DataTable({
            language: {
                url: '{{ asset("js/json/data_Table_pt_br.json") }}'
            }
        });
    });
</script>
@endsection