@extends('_theme')

@section("css")
<link rel="stylesheet" href="{{asset("css/dataTables.css")}}" />
<link rel="stylesheet" href="{{asset("css/toastr.min.css")}}" />
@endsection

@section('content')
<div class="container mt-5">
    <h2 class="text-center">{{$route == "P" ? "Pagamentos" : "Recebimentos"}}</h2>
    <div class="d-flex justify-content-end align-items-center mb-3">
        <!-- Align button to the right on larger screens -->
        <a href="{{ $route == "P" ? route('lancamentos.pagamentos.create') : route('lancamentos.recebimentos.create')}}"
            class="btn btn-primary">Cadastrar Conta à
            {{$route == "P" ? "Pagar" : "Receber"}}</a>
    </div>
    <!-- <div class="d-flex justify-content-start mb-3">
        <label for="start_date">Data Início:</label>
        <input type="" id="start_date" class="form-control mx-2" style="width: 150px;">
        <label for="end_date">Data Fim:</label>
        <input type="date" id="end_date" class="form-control mx-2" style="width: 150px;">
        <button class="btn btn-secondary" id="filter_button">Filtrar</button>
    </div> -->

    @if (!$lancamentos)
        <p class="text-center">Nenhum {{$route == "P" ? "Pagamento" : "Recebimento"}} Cadastrado.</p>
    @else
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Nº</th>
                        <th>Data Vencimento</th>
                        <th>Descrição</th>
                        <th>Valor</th>
                        <th>Data de {{$route == "P" ? "Pagamento" : "Recebimento"}}</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lancamentos as $lancamento)
                                @php
                                    // Comparando apenas a data sem hora (ano-mês-dia)
                                    $dataVencimento = date('Y-m-d', strtotime($lancamento->data_venc));
                                    $dataAtual = date('Y-m-d'); // Pega a data atual sem hora
                                    // Verifica se a data de vencimento é menor que a data atual
                                    $isVencido = $dataVencimento < $dataAtual;
                                @endphp

                                <tr class="{{ $isVencido ? 'table-danger' : '' }}">
                                    <td>{{ $lancamento->id }}</td>
                                    <td>{{ date('d/m/Y', strtotime($lancamento->data_venc)) }}</td>
                                    <td>{{ mb_strimwidth("$lancamento->descricao", 0, 25, "...") }}</td>
                                    <td>R$ {{ number_format($lancamento->valor, 2, ",", ".") }}</td>
                                    <td>{{ $lancamento->baixa ? date('d/m/Y', strtotime($lancamento->data_venc)) : '-' }}</td>
                                    <td>
                                        <a href="{{ route('lancamentos.pagamentos.update', $lancamento) }}"
                                            class="btn btn-sm btn-warning"><i class="fa-solid fa-pen-to-square"
                                                style="color: white;"></i></a>
                                        <form action="{{ route('lancamentos.pagamentos.destroy', $lancamento) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Tem certeza que deseja excluir?')"><i
                                                    class="fa-solid fa-trash"></i></button>
                                        </form>
                                        @if(!$lancamento->data_baixa)
                                            <a href="{{ route('lancamentos.pagamentos.baixa', $lancamento) }}"
                                                class="btn btn-sm btn-success"><i class="fa-solid fa-dollar-sign"></i></a>
                                        @endif
                                    </td>
                                </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
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
        // Filtrando os dados com base no intervalo de datas
        $('#filter_button').on('click', function () {
            // Obtendo as datas selecionadas
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();

            // Filtrando as linhas da tabela com base nas datas
            table.columns(1).search(
                function (data, type, row) {
                    // Converte as datas do DataTables para o formato YYYY-MM-DD
                    var dataVenc = data.split('/').reverse().join('-'); // Ex: "2024-11-26"

                    // Verifica se a data de vencimento está dentro do intervalo
                    if (startDate && endDate) {
                        return (dataVenc >= startDate && dataVenc <= endDate);
                    }
                    return true;
                },
                true, // regex
                false // smart
            ).draw();
        });
    });
</script>
@endsection