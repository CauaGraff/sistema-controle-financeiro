@extends('_theme')

@section("css")
<link rel="stylesheet" href="{{asset("css/dataTables.css")}}" />
<link rel="stylesheet" href="{{asset("css/toastr.min.css")}}" />
@endsection

@section('content')
<div class="container">
    <h1>Contas Bancárias</h1>
    <div class="d-flex justify-content-end align-items-center mb-3">
        <a href="{{ route('contas_banco.create') }}" class="btn btn-primary">Cadastrar Nova Conta</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped stripe row-border order-column" style="width:100%">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Agência</th>
                    <th>Conta</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contas as $conta)
                <tr>
                    <td>{{ $conta->id }}</td>
                    <td>{{ $conta->nome }}</td>
                    <td>{{ $conta->agencia }}</td>
                    <td>{{ $conta->conta }}</td>
                    <td class="text-center">
                        <a href="{{ route('contas_banco.edit', $conta->id) }}" class="btn btn-warning btn-sm"><i
                                class="fa-solid fa-pen-to-square" style="color: white;"></i></a>
                        <form action="{{ route('contas_banco.destroy', $conta->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Deseja realmente excluir esta conta bancária?')"><i class="fa-solid fa-trash"></i>
                        </form></button>
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
    $(document).ready(function() {
        var table = $(".table").DataTable({
            language: {
                url: '{{asset("js/json/data_Table_pt_br.json")}}'
            }
        });
    })
</script>
@endsection