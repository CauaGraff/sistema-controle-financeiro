@extends('_theme')

@section("css")
<link rel="stylesheet" href="{{asset("css/dataTables.css")}}" />
<link rel="stylesheet" href="{{asset("css/toastr.min.css")}}" />
@endsection

@section('content')
<div class="container">
    <h1>Contas Bancárias</h1>
    <a href="{{ route('contas_banco.create') }}" class="btn btn-primary">Cadastrar Nova Conta</a>

    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif
    <div class="table-responsive">
        <table class="table table-striped">
            <thead class="table-dark">
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
</div>
@endsection

@section('js')
<script src="{{asset("js/dataTables.js")}}"></script>
<script src="{{asset("js/toastr.min.js")}}"></script>
<script>
    $(document).ready(function () {
        var table = $(".table").DataTable({
            language: {
                url: '{{asset("js/json/data_Table_pt_br.json")}}'
            }
        });
    })
</script>
@endsection