@extends('_theme')

@section('title', 'Contas Bancárias')

@section('css')
<link rel="stylesheet" href="{{ asset('css/dataTables.css') }}" />
<link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}" />
@endsection

@section('content')
<div class="container mt-4">
    <h1>Contas Bancárias</h1>
    <a href="{{ route('contas_banco.create') }}" class="btn btn-primary mb-3">Cadastrar Nova Conta Bancária</a>
    <div class="table-responsive">

        <table id="contasBancoTable" class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Agência</th>
                    <th>Conta</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contas as $conta)
                    <tr>
                        <td>{{ $conta->id }}</td>
                        <td>{{ $conta->nome }}</td>
                        <td>{{ $conta->agencia }}</td>
                        <td>{{ $conta->conta }}</td>
                        <td>
                            <a href="{{ route('contas_banco.edit', $conta->id) }}" class="btn btn-warning btn-sm">
                                <i class="fa-solid fa-pen-to-square" style="color: white;"></i>
                            </a>
                            <form action="{{ route('contas_banco.destroy', $conta->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
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
<script src="{{ asset('js/toastr.min.js') }}"></script>

<script>
    $("#contasBancoTable").DataTable({
        language: {
            url: '{{ asset("js/json/data_Table_pt_br.json") }}',
        }
    });
</script>
@endsection