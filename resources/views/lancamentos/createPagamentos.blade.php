@extends('_theme')

@section('css')
<style>
    /* Estilos gerais */
    body {
        font-family: sans-serif;
    }

    .container {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    h1 {
        text-align: center;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        font-weight: bold;
    }

    .form-control,
    .form-select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .invalid-feedback {
        color: red;
        font-size: 14px;
    }

    .btn-primary {
        background-color: #007bff;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-primary:hover {
        background-color: #0069d9;
    }

    /* Estilos específicos para os campos de parcelamento e recorrência */
    #parcelasFields,
    #recorrenteFields {
        border: 1px solid #eee;
        padding: 10px;
        margin-top: 10px;
        border-radius: 5px;
    }
</style>
@endsection

@section('content')
<div class="container">
    <h1>Cadastrar Pagamentos</h1>
    <form action="{{ route('lancamentos.pagamentos.store') }}" method="POST" id="formPagamento">
        @csrf

        <!-- Campo de Descrição -->
        <div class="row mb-3">
            <label for="descricao" class="col-sm-2 col-form-label">Descrição</label>
            <div class="col-sm-10">
                <input type="text" class="form-control @error('descricao') is-invalid @enderror" id="descricao"
                    name="descricao" value="{{ old('descricao') }}">
                @error('descricao')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- Campo de Tipo do Cadastro -->
        <div class="row mb-3">
            <label for="tipo" class="col-sm-2 col-form-label">Tipo Pagamento</label>
            <div class="col-sm-10">
                <select class="form-select @error('tipo') is-invalid @enderror" id="tipo" name="tipo">
                    <option value="0" {{ old('tipo') == 0 ? 'selected' : '' }}>Padrão</option>
                    <option value="1" {{ old('tipo') == 1 ? 'selected' : '' }}>Parcelado</option>
                    <option value="2" {{ old('tipo') == 2 ? 'selected' : '' }}>Recorrente</option>
                </select>
                @error('tipo')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- Campos Padrão -->
        <div id="nenhumFields">
            <!-- Campo de Valor -->
            <div class="row mb-3">
                <label for="valor" class="col-sm-2 col-form-label" id="labelValor">Valor</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control @error('valor') is-invalid @enderror" id="valor" name="valor"
                        value="{{ old('valor') }}">
                    @error('valor')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <!-- Campo de Data de Vencimento -->
            <div class="row mb-3">
                <label for="data" class="col-sm-2 col-form-label" id="labelDataVenc">Data de Vencimento</label>
                <div class="col-sm-10">
                    <input type="date" name="data" id="data" class="form-control @error('data') is-invalid @enderror"
                        value="{{ old('data') }}">
                    @error('data')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Campos para Lançamentos Recorrentes -->
        <div id="recorrenteFields" style="display: none;">
            <div class="row mb-3">
                <label for="frequencia" class="col-sm-2 col-form-label">Frequência</label>
                <div class="col-sm-10">
                    <select class="form-select @error('frequencia') is-invalid @enderror" id="frequencia"
                        name="frequencia">
                        <option value="diaria" {{ old('frequencia') == 'diaria' ? 'selected' : '' }}>Diária</option>
                        <option value="semanal" {{ old('frequencia') == 'semanal' ? 'selected' : '' }}>Semanal</option>
                        <option value="mensal" {{ old('frequencia') == 'mensal' ? 'selected' : '' }}>Mensal</option>
                        <option value="anual" {{ old('frequencia') == 'anual' ? 'selected' : '' }}>Anual</option>
                    </select>
                    @error('frequencia')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="data_fim" class="col-sm-2 col-form-label">Fim da Recorrência</label>
                <div class="col-sm-10">
                    <input type="date" name="data_fim" id="data_fim"
                        class="form-control @error('data_fim') is-invalid @enderror" value="{{ old('data_fim') }}">
                    @error('data_fim')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Campos para Lançamentos Parcelados -->
        <div id="parcelasFields" style="display: none;">
            <!-- Campo de Valor Total -->
            <div class="row mb-3">
                <label for="valorTotal" class="col-sm-2 col-form-label" id="labelValor">Valor Total</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control @error('valorTotal') is-invalid @enderror" id="valorTotal"
                        name="valorTotal" value="{{ old('valorTotal') }}">
                    @error('valorTotal')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <!-- Campo de 1º Data de Vencimento -->
            <div class="row mb-3">
                <label for="dataVencPar" class="col-sm-2 col-form-label" id="labelDataVenc">1º Data de
                    Vencimento</label>
                <div class="col-sm-10">
                    <input type="date" name="dataVencPar" id="dataVencPar"
                        class="form-control @error('dataVencPar') is-invalid @enderror"
                        value="{{ old('dataVencPar') }}">
                    @error('dataVencPar')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <!-- Campo de Entrada -->
            <div class="row mb-3">
                <label for="valorEntrada" class="col-sm-2 col-form-label" id="labelEntrada">Valor Entrada</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control @error('valorEntrada') is-invalid @enderror"
                        id="valorEntrada" name="valorEntrada" value="{{ old('valorEntrada') }}">
                    @error('valorEntrada')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <!-- Campo de Quantidade de Parcelas -->
            <div class="row mb-3">
                <label for="qtdParcelas" class="col-sm-2 col-form-label" id="labelQtdParcelas">Quantidade de
                    Parcelas</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control @error('qtdParcelas') is-invalid @enderror"
                        id="qtdParcelas" name="qtdParcelas" value="{{ old('qtdParcelas') }}">
                    @error('qtdParcelas')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Campo de Categoria -->
        <div class="row mb-3">
            <label for="categoria" class="col-sm-2 col-form-label">Categoria</label>
            <div class="col-sm-10">
                <select class="form-select @error('categoria_id') is-invalid @enderror selectpicker" id="categoria"
                    name="categoria_id" data-live-search="true">
                    <option value="">Selecione uma categoria</option>
                    @foreach ($categoriasAgrupadas as $grupo)
                        @if (isset($grupo['categoria']))
                            <optgroup label="{{ $grupo['categoria']->descricao }}">
                                @foreach ($grupo['subcategorias'] as $subcategoria)
                                    <option value="{{ $subcategoria->id }}" {{ old('categoria_id') == $subcategoria->id ? 'selected' : '' }}>{{ $subcategoria->descricao }}</option>
                                @endforeach
                            </optgroup>
                        @endif
                    @endforeach
                </select>
            </div>
            @error('categoria_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Campo de Fornecedor/Cliente -->
        <div class="row mb-3">
            <label for="fornecedor_cliente" class="col-sm-2 col-form-label">Fornecedor/Cliente</label>
            <div class="col-sm-10">
                <select class="form-select @error('fornecedor_cliente_id') is-invalid @enderror" id="fornecedor_cliente"
                    name="fornecedor_cliente_id">
                    <option value="">Selecione o Fornecedor/Cliente</option>

                    <!-- Grupo de Fornecedores -->
                    <optgroup label="Fornecedores">
                        @foreach ($fornecedores as $fornecedor)
                            <option value="{{ $fornecedor->id }}" {{ old('fornecedor_cliente_id') == $fornecedor->id ? 'selected' : '' }}>{{ $fornecedor->nome }}</option>
                        @endforeach
                    </optgroup>

                    <!-- Grupo de Clientes -->
                    <optgroup label="Clientes">
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ old('fornecedor_cliente_id') == $cliente->id ? 'selected' : '' }}>{{ $cliente->nome }}</option>
                        @endforeach
                    </optgroup>
                </select>

                @error('fornecedor_cliente_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Cadastrar Lançamento</button>
    </form>
</div>
@endsection

@section('js')
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script>
    $(document).ready(function () {
        function selecionaCampos() {
            if ($("#tipo").val() == 0) {
                $("#nenhumFields").show();
                $("#recorrenteFields").hide();
                $("#parcelasFields").hide();
                $('#frequencia').val('');
                $('#data_fim').val('');
            } else if ($("#tipo").val() == 1) {
                $("#nenhumFields").hide();
                $("#recorrenteFields").hide();
                $("#parcelasFields").show();
                $('#frequencia').val('');
                $('#data_fim').val('');
            } else if ($("#tipo").val() == 2) {
                $("#nenhumFields").show();
                $("#recorrenteFields").show();
                $("#parcelasFields").hide();
            }
        }

        selecionaCampos();

        $('#valor, #valorTotal, #valorEntrada').mask('000.000.000.000.000,00', {
            reverse: true
        });

        $("#tipo").change(function () {
            selecionaCampos();
        });
    });
</script>
@endsection