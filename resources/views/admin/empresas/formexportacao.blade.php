<!-- Tab: Detalhes (Exportação) -->
<div class="p-3">
    <h4>Exportar Dados Contábeis</h4>
    <form method="POST" action="{{route('exportar.contabilidade', [$empresa->id])}}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="competencia" class="form-label">Copetencia</label>
            <input type="text" class="form-control" id="competencia" name="competencia">
        </div>
        <button type="submit" class="btn btn-primary">Exportar</button>
    </form>
</div>

@section('js')
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#competencia').mask('00/0000');
    });
</script>
@endsection