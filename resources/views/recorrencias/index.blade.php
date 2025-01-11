@extends('_theme')

@section("css")
<link rel="stylesheet" href="{{asset("css/dataTables.css")}}" />
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
<h1>Recorrências</h1>

<table class="table table-striped stripe row-border order-column" style="width:100%">
    <thead class="table-dark">
        <tr>
            <th>Descrição</th>
            <th>Data Início</th>
            <th>Data Fim</th>
            <th>Valor</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($recorrencias as $recorrencia)
        <tr>
            <td>{{ $recorrencia->descricao }}</td>
            <td>{{ \Carbon\Carbon::parse($recorrencia->data_inicio)->format('d/m/Y') }}</td>
            <td>{{ $recorrencia->data_fim ? \Carbon\Carbon::parse($recorrencia->data_fim)->format('d/m/Y') : 'Sem data fim' }}</td>
            <td>R$ {{ number_format($recorrencia->valor, 2, ',', '.') }}</td>
            <td>
                <a href="{{ route('recorrencias.edit', $recorrencia->id) }}" class="btn btn-warning btn-sm"><i
                        class="fa-solid fa-pen-to-square" style="color: white;"></i></a>
                <form action="{{ route('recorrencias.destroy', $recorrencia->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection

@section('js')
<script src="{{asset("js/dataTables.js")}}"></script>
<script src="{{asset("js/dataTables.fixedColumns.js")}}"></script>
<script src="{{asset("js/fixedColumns.dataTables.js")}}"></script>
<script>
    $(document).ready(function() {
        var table = $(".table").DataTable({
            language: {
                url: '{{asset("js/json/data_Table_pt_br.json")}}'
            },
            fixedColumns: {
                start: 0,
                end: 1
            },
            scrollX: true
        });
    });
</script>
@endsection