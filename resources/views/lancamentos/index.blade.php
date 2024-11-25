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
    <br>
    @if (!$lancamentos)
        <p class="text-center">Nenhum Pagamento Cadastrado.</p>
    @else
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Nº</th>
                        <th>Data Vencimento</th>
                        <th>Descrição</th>
                        <th>Valor</th>
                        <th>Data Baixa</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lancamentos as $lancamento)
                        <tr>
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
                                        onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</button>
                                </form>
                                @if(!$lancamento->data_baixa)
                                    <a href="{{ route('lancamentos.pagamentos.baixa', $lancamento) }}"
                                        class="btn btn-sm btn-success">Pagar</a>
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
        $(".table").DataTable({
            language: {
                url: '{{asset("js/json/data_Table_pt_br.json")}}'
            }
        });
    });
</script>
@endsection