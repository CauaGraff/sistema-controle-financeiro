@extends('_theme')

@section("css")
<link rel="stylesheet" href="{{asset("css/dataTables.css")}}" />
<style>
    .btn:disabled {
        pointer-events: none;
        /* Impede qualquer interação */
        opacity: 0.5;
        /* Dá uma aparência de "desabilitado" */
    }

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
    <div class="">
        <table class="table table-striped stripe row-border order-column" style="width:100%">
            <thead class=" table-dark">
                <tr>
                    <th>Nº</th>
                    <th>Descrição</th>
                    <th>Data Vencimento</th>
                    <th>Valor</th>
                    <th>Data de {{$route == "P" ? "Pagamento" : "Recebimento"}}</th>
                    <th>Valor {{$route == "P" ? "Pago" : "Recebido"}}</th>
                    <th class="text-center">Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($lancamentos as $lancamento)
                @php
                $pago = $lancamento->data_baixa !== null || $lancamento->lancamentoBaixa; // Verifica se existe data de baixa ou se o relacionamento foi preenchido
                $isVencido = date('Y-m-d', strtotime($lancamento->data_venc)) < date('Y-m-d'); // Verifica se a data de vencimento é anterior à data atual
                    @endphp

                    <tr class="{{ $pago ? 'table-success' : ($isVencido ? 'table-danger' : '') }}">
                    <td>{{ $lancamento->id }}</td>
                    <td>{{ mb_strimwidth("$lancamento->descricao", 0, 25, "...") }}</td>
                    <td>{{ date('d/m/Y', strtotime($lancamento->data_venc)) }}</td>
                    <td>R$ {{ number_format($lancamento->valor, 2, ",", ".") }}</td>
                    <td>{{ $lancamento->lancamentoBaixa ? date('d/m/Y', strtotime($lancamento->lancamentoBaixa->created_at)) : '-' }}
                    </td>
                    <td>{{ $lancamento->lancamentoBaixa ? "R$" . number_format($lancamento->lancamentoBaixa->valor, 2, ",", ".") : '-'}}
                    </td>
                    @if ($route == "P")
                    <td class="text-center">
                        <a href="{{ route('lancamentos.edit', $lancamento) }}" class="btn btn-sm btn-warning"><i
                                class="fa-solid fa-pen-to-square" style="color: white;"></i></a>
                        <form action="{{ route('lancamentos.pagamentos.destroy', $lancamento) }}" method="POST"
                            style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('Tem certeza que deseja excluir?')"><i
                                    class="fa-solid fa-trash"></i></button>
                        </form>
                        <a href="{{ $pago ? '#' : route('lancamentos.pagamentos.baixa', $lancamento) }}"
                            class="btn btn-sm btn-success {{ $pago ? 'disabled' : '' }}"
                            style="{{ $pago ? 'opacity: 0.5;' : '' }}" @if($pago) aria-disabled="true" @endif>
                            <i class="fa-solid fa-dollar-sign"></i>
                        </a>
                    </td>
                    @else
                    <td class="text-center">
                        <a href="{{ route('lancamentos.edit', $lancamento) }}" class="btn btn-sm btn-warning"><i
                                class="fa-solid fa-pen-to-square" style="color: white;"></i></a>
                        <form action="{{ route('lancamentos.recebimentos.destroy', $lancamento) }}" method="POST"
                            style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('Tem certeza que deseja excluir?')"><i
                                    class="fa-solid fa-trash"></i></button>
                        </form>
                        <a href="{{ $pago ? '#' : route('lancamentos.recebimentos.baixa', $lancamento) }}"
                            class="btn btn-sm btn-success {{ $pago ? 'disabled' : '' }}"
                            style="{{ $pago ? 'opacity: 0.5;' : '' }}" @if($pago) aria-disabled="true" @endif>
                            <i class="fa-solid fa-dollar-sign"></i>
                        </a>
                    </td>
                    @endif
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
        // Filtrando os dados com base no intervalo de datas
        $('#filter_button').on('click', function() {
            // Obtendo as datas selecionadas
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();

            // Filtrando as linhas da tabela com base nas datas
            table.columns(1).search(
                function(data, type, row) {
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