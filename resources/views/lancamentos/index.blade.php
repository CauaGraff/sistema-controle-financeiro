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

        /* Estilos para o filtro lateral */
        #filter-side {
            position: fixed;
            top: 0;
            left: -300px;
            /* Começa fora da tela */
            width: 300px;
            height: 100%;
            background-color: #f8f9fa;
            /* Fundo mais suave */
            color: #343a40;
            padding: 5px 20px;
            transition: left 0.3s ease-in-out;
            z-index: 9999;
            box-shadow: 4px 0 6px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }

        #filter-side.open {
            left: 0;
            /* Fica visível quando a classe "open" é adicionada */
        }

        #filter-side .close-btn {
            background: transparent;
            border: none;
            font-size: 30px;
            color: #343a40;
            cursor: pointer;
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
        <!-- Ícone de filtro -->
        <div class="d-flex justify-content-between mb-3 g-2">
            <button id="filter-toggle" class="btn btn-outline-primary">
                <i class="fa-solid fa-filter"></i> Filtro
            </button>
            <button class="btn btn-outline-primary" id="export">
                <i class="fa-solid fa-file-export"></i> Exportar
            </button>
        </div>
        @if (!$lancamentos)
            <p class="text-center">Nenhum {{$route == "P" ? "Pagamento" : "Recebimento"}} Cadastrado.</p>
        @else
            <div class="">
                <table class="table table-striped stripe row-border order-column" style="width:100%">
                    <thead class="table-dark">
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

    @include('lancamentos.formFilterTables')
@endsection

@section('js')
    <script src="{{asset("js/dataTables.js")}}"></script>
    <script src="{{asset("js/dataTables.fixedColumns.js")}}"></script>
    <script src="{{asset("js/fixedColumns.dataTables.js")}}"></script>
    <script src="{{ asset('js/select2.full.min.js') }}"></script>





    <script>
        $(document).ready(function () {
            $("#filter-toggle").on("click", function () {
                $("#filter-side").toggleClass("open");
            });

            $("#close-filter").on("click", function () {
                $("#filter-side").removeClass("open");
            });
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

            $("#export").on("click", function (e) {
                e.preventDefault(); // Previne o comportamento padrão do link

                var formData = $("#form-filter").serialize(); // Coleta os dados dos filtros do formulário
                var token = "{{ csrf_token() }}"; // Obtenha o token CSRF

                $.ajax({
                    url: "{{route('lancamentos.export')}}", // A URL da rota de exportação
                    type: "POST",
                    data: formData + "&_token=" + token, // Concatenando o token com os dados do formulário
                    xhrFields: {
                        responseType: 'blob' // Receber o conteúdo como arquivo binário
                    },
                    success: function (data, status, xhr) {
                        const filename = xhr.getResponseHeader('Content-Disposition')
                            .split('filename=')[1]
                            .replace(/["']/g, '');

                        const blob = new Blob([data], { type: 'text/csv' });
                        const link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = filename;
                        document.body.appendChild(link);
                        link.click();
                        link.remove();
                    },
                    error: function () {
                        alert('Erro ao gerar o arquivo.');
                    }
                });
            });

        });
    </script>
@endsection