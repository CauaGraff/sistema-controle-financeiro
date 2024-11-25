@extends('_theme')

@section('content')
<div class="container mt-5">
    <h1>Cadastrar Lançamento</h1>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('lancamentos.pagamentos.store') }}" method="POST">
        @csrf

        <!-- Campo de Descrição -->
        <div class="row mb-3">
            <label for="descricao" class="col-sm-2 col-form-label">Descrição</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="descricao" name="descricao">
            </div>
            @error('descricao')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <!-- Campo de tipo do cadastro -->
        <div class="row mb-3">
            <label for="recorrente" class="col-sm-2 col-form-label">Tipo</label>
            <div class="col-sm-10">
                <select class="form-select" id="tipo" name="tipo">
                    <option value="0">Nenhum</option>
                    <option value="1">Parcelas</option>
                    <option value="2">Recorrente</option>
                </select>
            </div>
        </div>
        <!-- Campos normal -->
        <div id="nenhumFields">
            <!-- Campo de Valor -->
            <div class="row mb-3">
                <label for="valor" class="col-sm-2 col-form-label" id="labelValor">Valor</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="valor" name="valor">
                </div>
            </div>
            <!-- Campo de Data de Vencimento -->
            <div class="row mb-3">
                <label for="data_vencimento" class="col-sm-2 col-form-label" id="labelDataVenc">Data de
                    Vencimento</label>
                <div class="col-sm-10">
                    <input type="date" name="data" id="data" class="form-control">
                </div>
            </div>
        </div>

        <!-- Campos adicionais para lançamentos recorrentes -->
        <div id="recorrenteFields" style="display: none;">
            <div class="row mb-3">
                <label for="frequencia" class="col-sm-2 col-form-label">Frequência</label>
                <div class="col-sm-10">
                    <select class="form-select" id="frequencia" name="frequencia">
                        <option value="mensal">Mensal</option>
                        <option value="anual">Anual</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Campos adicionais para lançamentos Parcelas -->
        <div id="parcelasFields" style="display: none;">
            <!-- Campo de Valor -->
            <div class="row mb-3">
                <label for="valor" class="col-sm-2 col-form-label" id="labelValor">Valor Total</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="valorTotal" name="valorTotal">
                </div>
            </div>
            <!-- Campo de Data de Vencimento -->
            <div class="row mb-3">
                <label for="data" class="col-sm-2 col-form-label" id="labelDataVenc">1º Data de
                    Vencimento</label>
                <div class="col-sm-10">
                    <input type="date" name="data" id="data" class="form-control">
                </div>
            </div>
            <!-- Campo de Entrada -->
            <div class="row mb-3">
                <label for="valorEntrada" class="col-sm-2 col-form-label" id="labelEntrada">Valor Entrada</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="valorEntrada" name="valorEntrada">
                </div>
            </div>

            <!-- Campo de qtd parcelas -->
            <div class="row mb-3">
                <label for="qtdParcelas" class="col-sm-2 col-form-label" id="labelDataVenc">Quantidade Parcelas</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" id="qtdParcelas" name="qtdParcelas">
                </div>
            </div>
        </div>

        <!-- Campo de Categoria -->
        <div class="row mb-3">
            <label for="categoria" class="col-sm-2 col-form-label">Categoria</label>
            <div class="col-sm-10">
                <select class="form-select" id="categoria" name="categoria_id">
                    <option value="">Selecione uma categoria</option>
                    @foreach ($categoriasAgrupadas as $grupo)
                        @if (isset($grupo['categoria']))
                            <optgroup label="{{ $grupo['categoria']->descricao }}">
                                @foreach ($grupo['subcategorias'] as $subcategoria)
                                    <option value="{{ $subcategoria->id }}">{{ $subcategoria->descricao }}</option>
                                @endforeach
                            </optgroup>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Campo de Favorecido -->
        <div class="row mb-3">
            <label for="fornecedor_cliente" class="col-sm-2 col-form-label">Fornecedor/Cleinte</label>
            <div class="col-sm-10">
                <select class="form-select" id="fornecedor_cliente" name="fornecedor_cliente_id">
                    <option value="">Selecione o favorecido</option>
                    @foreach ($fornecedores as $fornecedor)
                        <option value="{{ $fornecedor->id }}">{{ $fornecedor->nome }}</option>
                    @endforeach
                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Cadastrar Lançamento</button>
    </form>
</div>
@endsection

@section('js')
<script src="{{asset("js/jquery.mask.min.js")}}"></script>
<script>

    $(document).ready(function () {
        $('#valor').mask('000.000.000.000.000,00', { reverse: true });
        $('#valorTotal').mask('000.000.000.000.000,00', { reverse: true });
        $('#valorEntrada').mask('000.000.000.000.000,00', { reverse: true });
        $("#tipo").change(function () {
            if ($(this).val() == 0) {
                $("#nenhumFields").show()
                $("#recorrenteFields").hide()
                $("#parcelasFields").hide()
            } else if ($(this).val() == 1) {
                $("#nenhumFields").hide()
                $("#recorrenteFields").hide()
                $("#parcelasFields").show()
            } else if ($(this).val() == 1) {
                $("#nenhumFields").hide()
                $("#recorrenteFields").show()
                $("#parcelasFields").hide()
            }
        })
    })

</script>
@endsection