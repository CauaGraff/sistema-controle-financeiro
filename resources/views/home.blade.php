@extends('_theme')

@section('css') 
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
</style>
@endsection

@section('content')
<div class="container">
    <h1>Calendário de Lançamentos</h1>

    <div class="row">
        <div class="col-md-10">
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
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>

<script>
    $(document).ready(function () {
        let currentMonth = moment().month(); // Mês atual (0-11)
        let currentYear = moment().year();  // Ano atual

        // Função para gerar o calendário
        function generateCalendar(month, year) {
            $.ajax({
                url: '{{ route('calendario.getEventos') }}',
                method: 'GET',
                data: { month: month + 1, year: year }, // Envia o mês (1-12) e o ano
                success: function (events) {
                    $('#calendar-body').empty();
                    $('#month-title').text(moment([year, month]).format('MMMM YYYY'));

                    let firstDayOfMonth = moment([year, month]).startOf('month').day();
                    let daysInMonth = moment([year, month]).daysInMonth();

                    let calendarHTML = '';
                    let day = 1;

                    // Preencher as semanas
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
                                    // Se o evento existe, aplica a classe de cor de fundo
                                    calendarHTML += `<td class="text-center position-relative ${event[0].statusClass}">`;
                                } else {
                                    // Caso contrário, mantém a célula sem cor de fundo
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
                }
            });
        }

        // Carrega o calendário inicial
        generateCalendar(currentMonth, currentYear);

        // Mês anterior
        $('#prev-month').click(function () {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            generateCalendar(currentMonth, currentYear);
        });

        // Próximo mês
        $('#next-month').click(function () {
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