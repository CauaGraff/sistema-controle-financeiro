@extends('_theme')

@section('css')
<link rel="stylesheet" href="{{asset('css/dataTables.css')}}" />
<style>
    /* Estilo para as tabelas e cards */
    .card {
        margin-bottom: 20px;
    }

    .table-container {
        display: flex;
        gap: 20px;
        margin-top: 20px;
    }

    .table-container .table {
        flex: 1;
    }

    .table tbody tr td:hover {
        background-color: #DDD;
    }

    a .btn:disabled {
        pointer-events: none;
        opacity: 0.5;
    }

    /* Adicionando maior responsividade */
    @media (max-width: 576px) {
        .calendar-nav-btn {
            padding: 8px;
            font-size: 0.8rem;
        }

        #month-title {
            font-size: 1.2rem;
        }

        .table-container {
            flex-direction: column;
        }

        .table {
            width: 100%;
            overflow-x: auto;
            /* Habilita rolagem horizontal em tabelas */
        }

        .table td,
        .table th {
            font-size: 0.9rem;
            /* Reduz o tamanho da fonte */
        }

        .card {
            margin-bottom: 15px;
        }

        .card-header h5 {
            font-size: 1rem;
            /* Ajusta o tamanho do título */
        }

        .btn {
            font-size: 0.8rem;
            padding: 5px 10px;
        }

        .btn-actions {
            display: flex;
            justify-content: center;
            gap: 5px;
            /* Espaço entre os botões */
            flex-wrap: wrap;
            /* Permitir quebra de linha caso o espaço seja insuficiente */
        }

        .btn-actions .btn {
            flex: 1;
            min-width: 30%;
            padding: 5px;
            font-size: 0.8rem;
        }
    }


    /* Estilo do calendário */
    #calendar-body td {
        padding: 15px 10px;
        text-align: center;
        cursor: pointer;
        font-size: 1rem;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    #calendar-body td:hover {
        background-color: #f4f4f4;
        transition: background-color 0.3s ease;
    }

    #calendar-body td.selected {
        background-color: #007bff;
        color: white;
    }

    .calendar-header th {
        padding: 10px;
        text-align: center;
        font-size: 1.1rem;
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        font-weight: bold;
    }

    .calendar-header th:hover {
        background-color: #e9ecef;
    }

    .calendar-header {
        background-color: #f4f4f4;
    }

    #month-title {
        font-size: 1.5rem;
        font-weight: bold;
    }

    .calendar-nav-btn {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .calendar-nav-btn:hover {
        background-color: #0056b3;
    }

    .calendar-nav-btn:focus {
        outline: none;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row d-flex justify-content-center mt-4">
        <div class="col-md-10">
            <!-- Calendário -->
            <div class="d-flex justify-content-between mb-3">
                <button id="prev-month" class="calendar-nav-btn"><i class="fa-solid fa-angle-left fa-2xl"></i></button>
                <h3 id="month-title">{{ \Carbon\Carbon::now()->locale('pt-BR')->format('F Y') }}</h3>
                <button id="next-month" class="calendar-nav-btn"><i class="fa-solid fa-angle-right fa-2xl"></i></button>
            </div>

            <table class="table shadow-sm" style="background-color: transparent;" outline: none;>
                <thead class="calendar-header">
                    <tr>
                        <th>Dom</th>
                        <th>Seg</th>
                        <th>Ter</th>
                        <th>Qua</th>
                        <th>Qui</th>
                        <th>Sex</th>
                        <th>Sáb</th>
                    </tr>
                </thead>
                <tbody id="calendar-body">
                    <!-- Calendário será gerado aqui -->
                </tbody>
            </table>
        </div>
        <!-- Cards abaixo do calendário -->
        <div class="row mt-4">
            <!-- Card de Pagamento -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title"> Total Pagamentos
                        </h5>
                        <div class="card-text">
                            <h5 id="totalPagamentos">R$ 0,00</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card de Recebimento -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title"> Total Recebimentos </h5>
                        <div class="card-text">
                            <h5 id="totalRecebimentos">R$ 0,00</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card de Total Geral -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title"> Total Recebimentos </h5>
                        <div class="card-text">
                            <h5 id="totalGeral">R$ 0,00</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabelas de pagamentos e recebimentos -->
    <div class="row mt-4">
        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Pagamentos</h5>
                    <a href="{{ route('lancamentos.pagamentos.index')}}" class="btn btn-primary">Ver todos</a>
                </div>
                <div class="card-body">
                    <table id="table-pagamentos" class="table table-striped"></table>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12 mt-4 mt-md-0">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Recebimentos</h5>
                    <a href="{{ route('lancamentos.recebimentos.index')}}" class="btn btn-primary">Ver todos</a>
                </div>
                <div class="card-body">
                    <table id="table-recebimentos" class="table table-striped"></table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="{{asset('js/dataTables.js')}}"></script>
<script src="{{asset('js/dataTables.fixedColumns.js')}}"></script>
<script src="{{asset('js/fixedColumns.dataTables.js')}}"></script>
<script>
    $(document).ready(function () {
        $("main section").removeClass("container");
        let currentMonth = moment().month(); // Mês atual (0-11)
        let currentYear = moment().year(); // Ano atual
        let selectedDate = null; // Inicializamos a variável da data selecionada

        // Configuração do idioma para português
        moment.locale('pt'); // Certifique-se de definir o idioma como 'pt'

        function generateCalendar(month, year) {
            $.ajax({
                url: '{{ route("calendario.eventos.post") }}',
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    month: month + 1,
                    year: year
                },
                success: function (events) {
                    $('#calendar-body').empty();

                    // Atualizando o título do mês para português
                    $('#month-title').text(moment([year, month]).format('MMMM YYYY')); // Usando 'MMMM' para o nome completo do mês

                    let firstDayOfMonth = moment([year, month]).startOf('month').day();
                    let daysInMonth = moment([year, month]).daysInMonth();
                    let calendarHTML = '';
                    let day = 1;

                    for (let i = 0; i < 6; i++) {
                        calendarHTML += '<tr>';
                        for (let j = 0; j < 7; j++) {
                            if (i === 0 && j < firstDayOfMonth) {
                                calendarHTML += '<td></td>';
                            } else if (day > daysInMonth) {
                                calendarHTML += '<td></td>';
                            } else {
                                // Certificando que a data é passada no formato ISO
                                let currentDate = moment(`${year}-${month + 1}-${day}`, 'YYYY-MM-DD');
                                let event = events.filter(event => event.date === currentDate.format('YYYY-MM-DD'));
                                if (event.length > 0) {
                                    calendarHTML += `<td class="text-center position-relative ${event[0].statusClass}" data-date="${currentDate.format('YYYY-MM-DD')}">`;
                                } else {
                                    calendarHTML += `<td class="text-center" data-date="${currentDate.format('YYYY-MM-DD')}">`;
                                }
                                calendarHTML += '<span>' + day + '</span>';
                                calendarHTML += '</td>';
                                day++;
                            }
                        }
                        calendarHTML += '</tr>';
                        if (day > daysInMonth) break;
                    }
                    $('#calendar-body').html(calendarHTML);

                    // Atualiza tabelas sem filtro de data
                    updateTables(month, year);
                }
            });
        }

        // Função para atualizar as tabelas com base na data selecionada
        function updateTables(month, year, date = null) {
            $.ajax({
                url: '{{ route("calendario.lancamentos.post") }}',
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    month: month + 1,
                    year: year,
                    date: date // Envia a data caso tenha sido selecionada
                },
                success: function (data) {

                    // Atualizar os cards com os totais
                    $('#totalPagamentos').text('R$ ' + data.totais.pagamentos);
                    $('#totalRecebimentos').text('R$ ' + data.totais.recebimentos);
                    $('#totalGeral').text('R$ ' + data.totais.geral);
                    // Pagamentos
                    if ($.fn.DataTable.isDataTable('#table-pagamentos')) {
                        $('#table-pagamentos').DataTable().clear().destroy();
                    }

                    $('#table-pagamentos').DataTable({
                        language: {
                            url: '{{ asset("js/json/data_Table_pt_br.json") }}'
                        },
                        fixedColumns: {
                            start: 0,
                            end: 1
                        },
                        pageLength: 10,  // Fixando o número de registros por página (modifique conforme necessário)
                        lengthChange: false, // Removendo a opção de mudar o número de itens por página
                        scrollX: true,
                        data: data.pagamentos,
                        columns: [
                            { data: 'id', title: '#' },
                            { data: 'descricao', title: 'Descrição' },
                            { data: 'valor', title: 'Valor' },
                            { data: 'data_venc', title: 'Vencimento' },
                            {
                                data: null,
                                title: 'Ações',
                                render: function (data, type, row) {
                                    let editRoute = `{{ route('lancamentos.edit', ':id') }}`.replace(':id', row.id);
                                    let deleteRoute = `{{ route('lancamentos.pagamentos.destroy', ':id') }}`.replace(':id', row.id);
                                    let baixaRoute = `{{ route('lancamentos.pagamentos.baixa', ':id') }}`.replace(':id', row.id);

                                    let disabled = row.status === 'pago' ? 'disabled' : '';

                                    return `
        <div class="btn-actions">
            <a href="${editRoute}" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen-to-square" style="color: white;"></i></a>
            <form action="${deleteRoute}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
            </form>
            <a href="${baixaRoute}" class="btn btn-sm btn-success ${disabled}" ${disabled}><i class="fa-solid fa-dollar-sign"></i></a>
        </div>
    `;
                                }

                            }
                        ],
                        createdRow: function (row, data) {
                            // Aplica cor de fundo nas linhas da tabela
                            if (data.status === 'vencido') {
                                $(row).addClass('table-danger'); // Linha vermelha para vencido
                            } else if (data.status === 'pago') {
                                $(row).addClass('table-success'); // Linha verde para pago
                            } else if (data.status === 'pendente') {
                                $(row).addClass('');
                            }
                        },
                        headerCallback: function (thead, data, start, end, display) {
                            $(thead).addClass('table-dark');
                        }
                    });

                    // Recebimentos
                    if ($.fn.DataTable.isDataTable('#table-recebimentos')) {
                        $('#table-recebimentos').DataTable().clear().destroy();
                    }

                    $('#table-recebimentos').DataTable({
                        language: {
                            url: '{{ asset("js/json/data_Table_pt_br.json") }}'
                        },
                        fixedColumns: {
                            start: 0,
                            end: 1
                        },
                        pageLength: 10,  // Fixando o número de registros por página (modifique conforme necessário)
                        lengthChange: false, // Removendo a opção de mudar o número de itens por página
                        scrollX: true,
                        data: data.recebimentos,
                        columns: [
                            { data: 'id', title: '#' },
                            { data: 'descricao', title: 'Descrição' },
                            { data: 'valor', title: 'Valor' },
                            { data: 'data_venc', title: 'Vencimento' },
                            {
                                data: null,
                                title: 'Ações',
                                render: function (data, type, row) {
                                    let editRoute = `{{ route('lancamentos.edit', ':id') }}`.replace(':id', row.id);
                                    let deleteRoute = `{{ route('lancamentos.recebimentos.destroy', ':id') }}`.replace(':id', row.id);
                                    let baixaRoute = `{{ route('lancamentos.recebimentos.baixa', ':id') }}`.replace(':id', row.id);

                                    let disabled = row.status === 'recebido' ? 'disabled' : '';

                                    return `
        <div class="btn-actions">
            <a href="${editRoute}" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen-to-square" style="color: white;"></i></a>
            <form action="${deleteRoute}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
            </form>
                        <a href="${baixaRoute}" class="btn btn-sm btn-success ${disabled}" ${disabled}><i class="fa-solid fa-dollar-sign"></i></a>

        </div>
    `;
                                }

                            }
                        ],
                        createdRow: function (row, data) {
                            if (data.status === 'vencido') {
                                $(row).addClass('table-danger');
                            } else if (data.status === 'recebido') {
                                $(row).addClass('table-success');
                            }
                        },
                        headerCallback: function (thead, data, start, end, display) {
                            $(thead).addClass('table-dark');
                        }
                    });
                }
            });
        }

        // Inicializa o calendário do mês atual
        generateCalendar(currentMonth, currentYear);

        // Quando o usuário clica em uma data específica no calendário
        $('#calendar-body').on('click', 'td[data-date]', function () {
            // Remove a seleção anterior
            $('#calendar-body td').removeClass('selected');

            // Adiciona a classe de seleção na célula clicada
            $(this).addClass('selected');

            selectedDate = $(this).data('date'); // Obtém a data selecionada
            updateTables(currentMonth, currentYear, selectedDate); // Atualiza as tabelas com a data selecionada
        });

        // Navegação entre meses
        $('#prev-month').on('click', function () {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            generateCalendar(currentMonth, currentYear);
        });

        $('#next-month').on('click', function () {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            generateCalendar(currentMonth, currentYear);
        });
    });
</script>
@endsection