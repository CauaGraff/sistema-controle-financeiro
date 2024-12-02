@extends('_theme')

@section('content')
<div class="container">
    <h1>Contas Bancárias</h1>
    <a href="{{ route('contas_banco.create') }}" class="btn btn-primary">Cadastrar Nova Conta</a>

    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    <table class="table mt-3">
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
            @foreach($contas as $conta)
                <tr>
                    <td>{{ $conta->id }}</td>
                    <td>{{ $conta->nome }}</td>
                    <td>{{ $conta->agencia }}</td>
                    <td>{{ $conta->conta }}</td>
                    <td>
                        <a href="{{ route('contas_banco.edit', $conta->id) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('contas_banco.destroy', $conta->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Deseja realmente excluir esta conta bancária?')">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection