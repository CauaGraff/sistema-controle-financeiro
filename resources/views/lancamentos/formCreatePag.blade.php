@extends('_theme')

@section('content')
<div class="container mt-5">
    <h1>Cadastrar Pagamentos</h1>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('lancamentos.pagamentos.store') }}" method="POST">
        @csrf
        @include("lancamentos.form")
        <button type="submit" class="btn btn-primary">Cadastrar Lançamento</button>
    </form>
</div>
@endsection

@section('js')
<script src="{{asset("js/jquery.mask.min.js")}}"></script>
<script>
    $(document).ready(function () {
        function selecionaCampos() {
            if ($("#tipo").val() == 0) {
                $("#nenhumFields").show()
                $("#recorrenteFields").hide()
                $("#parcelasFields").hide()
            } else if ($("#tipo").val() == 1) {
                $("#nenhumFields").hide()
                $("#recorrenteFields").hide()
                $("#parcelasFields").show()
            } else if ($("#tipo").val() == 2) {
                $("#nenhumFields").show()
                $("#recorrenteFields").show()
                $("#parcelasFields").hide()
            }
        }
        selecionaCampos();
        $('#valor').mask('000.000.000.000.000,00', { reverse: true });
        $('#valorTotal').mask('000.000.000.000.000,00', { reverse: true });
        $('#valorEntrada').mask('000.000.000.000.000,00', { reverse: true });
        $("#tipo").change(function () {
            selecionaCampos();
        })
    })
</script>
@endsection