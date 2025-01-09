@extends('_theme')

@section('css')
<link rel="stylesheet" href="{{asset("css/dataTables.css")}}" />
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

    @media (max-width: 768px) {
        .card {
            margin-bottom: 20px;
            /* Espaço entre os cartões */
        }

        .table-container {
            flex-direction: column;
            /* Faz com que as tabelas fiquem empilhadas */
        }

        .table {
            width: 100%;
            /* Garante que a tabela ocupe toda a largura */
        }
    }
</style>

@endsection

@section('content')
<div class="container-fluid">
    <div class="row d-flex justify-content-center mt-4">
        <div class="col-md-10">
            <!-- Calendário -->
            <div class="d-flex justify-content-between mb-3">
                <button id="prev-month" class="btn"><i class="fa-solid fa-angle-left fa-2xl"></i></button>
                <h3 id="month-title">{{ \Carbon\Carbon::now()->format('F Y') }}</h3>
                <button id="next-month" class="btn"><i class="fa-solid fa-angle-right fa-2xl"></i></button>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless" style="background-color: transparent;">
                    <thead class="text-center">
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
<script src="{{asset("js/dataTables.js")}}"></script>
<script src="{{asset("js/dataTables.fixedColumns.js")}}"></script>
<script src="{{asset("js/fixedColumns.dataTables.js")}}"></script>
<script>
    $(document).ready(function() {
        $("main section").removeClass("container");
        let currentMonth = moment().month(); // Mês atual (0-11)
        let currentYear = moment().year(); // Ano atual

        function generateCalendar(month, year) {
            $.ajax({
                url: '{{ route("calendario.eventos.post") }}',
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    month: month + 1,
                    year: year
                },
                success: function(events) {
                    $('#calendar-body').empty();
                    $('#month-title').text(moment([year, month]).format('MMMM YYYY'));

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
                                let currentDate = moment([year, month]).date(day).format('YYYY-MM-DD');
                                let event = events.filter(event => event.date === currentDate);
                                if (event.length > 0) {
                                    calendarHTML += `<td class="text-center position-relative ${event[0].statusClass}">`;
                                } else {
                                    calendarHTML += '<td class="text-center">';
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

                    // Atualiza tabelas
                    updateTables(month, year);
                }
            });
        }

        function updateTables(month, year) {
            $.ajax({
                url: '{{ route("calendario.lancamentos.post") }}',
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    month: month + 1,
                    year: year
                },
                success: function(data) {
                    // Pagamentos
                    $('#table-pagamentos').DataTable({
                        language: {
                            url: '{{ asset("js/json/data_Table_pt_br.json") }}'
                        },
                        fixedColumns: {
                            start: 0,
                            end: 1
                        },
                        scrollX: true,
                        data: data.pagamentos,
                        columns: [{
                                data: 'id',
                                title: '#'
                            },
                            {
                                data: 'descricao',
                                title: 'Descrição'
                            },
                            {
                                data: 'valor',
                                title: 'Valor'
                            },
                            {
                                data: 'data_venc',
                                title: 'Vencimento',
                                render: function(data, type, row) {
                                    let vencido = moment().isAfter(data, 'day') && row.status !== 'pago';
                                    return `<span class="${vencido ? 'text-danger' : ''}">${data}</span>`;
                                }
                            },
                            {
                                data: null,
                                title: 'Ações',
                                render: function(data, type, row) {
                                    let editRoute = `{{ route('lancamentos.edit', ':id') }}`.replace(':id', row.id);
                                    let deleteRoute = `{{ route('lancamentos.pagamentos.destroy', ':id') }}`.replace(':id', row.id);
                                    let baixaRoute = `{{ route('lancamentos.pagamentos.baixa', ':id') }}`.replace(':id', row.id);

                                    return `
                                        <a href="${editRoute}" class="btn btn-sm btn-warning">Editar</a>
                                        <form action="${deleteRoute}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                        </form>
                                        <a href="${baixaRoute}" class="btn btn-sm btn-success">Baixar</a>
                                    `;
                                }
                            }
                        ],
                        createdRow: function(row, data) {
                            // Aplica cor de fundo nas linhas da tabela
                            if (data.status === 'vencido') {
                                $(row).addClass('table-danger'); // Linha vermelha para vencido
                            } else if (data.status === 'pago') {
                                $(row).addClass('table-success'); // Linha verde para pago
                            }
                        },
                        pageLength: 10,
                        lengthChange: false,
                        destroy: true
                    });

                    // Recebimentos
                    $('#table-recebimentos').DataTable({
                        language: {
                            url: '{{ asset("js/json/data_Table_pt_br.json") }}'
                        },
                        fixedColumns: {
                            start: 0,
                            end: 1
                        },
                        scrollX: true,
                        data: data.recebimentos,
                        columns: [{
                                data: 'id',
                                title: '#'
                            },
                            {
                                data: 'descricao',
                                title: 'Descrição'
                            },
                            {
                                data: 'valor',
                                title: 'Valor'
                            },
                            {
                                data: 'data_venc',
                                title: 'Vencimento',
                                render: function(data, type, row) {
                                    let vencido = moment().isAfter(data, 'day') && row.status !== 'pago';
                                    return `<span class="${vencido ? 'text-danger' : ''}">${data}</span>`;
                                }
                            },
                            {
                                data: null,
                                title: 'Ações',
                                render: function(data, type, row) {
                                    let editRoute = `{{ route('lancamentos.edit', ':id') }}`.replace(':id', row.id);
                                    let deleteRoute = `{{ route('lancamentos.recebimentos.destroy', ':id') }}`.replace(':id', row.id);
                                    let baixaRoute = `{{ route('lancamentos.recebimentos.baixa', ':id') }}`.replace(':id', row.id);

                                    return `
                                        <a href="${editRoute}" class="btn btn-sm btn-warning">Editar</a>
                                        <form action="${deleteRoute}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                        </form>
                                        <a href="${baixaRoute}" class="btn btn-sm btn-success">Baixar</a>
                                    `;
                                }
                            }
                        ],
                        createdRow: function(row, data) {
                            // Aplica cor de fundo nas linhas da tabela
                            if (data.status === 'vencido') {
                                $(row).addClass('table-danger'); // Linha vermelha para vencido
                            } else if (data.status === 'pago') {
                                $(row).addClass('table-success'); // Linha verde para pago
                            }
                        },
                        pageLength: 10,
                        lengthChange: false,
                        destroy: true
                    });
                }
            });
        }

        // Carrega o calendário inicial
        generateCalendar(currentMonth, currentYear);

        // Mês anterior
        $('#prev-month').click(function() {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            generateCalendar(currentMonth, currentYear);
        });

        // Próximo mês
        $('#next-month').click(function() {
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