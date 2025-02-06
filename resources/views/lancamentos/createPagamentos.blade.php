@extends('_theme')

@section('css')
<link rel="stylesheet" href="{{asset("css/select2.min.css")}}">
<link rel="stylesheet" href="{{asset("css/select2/select2-bootstrap-5-theme.min.css")}}">
@endsection

@section('content')
<div class="container mt-5">
    <h1>Cadastrar Pagamentos</h1>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('lancamentos.pagamentos.store') }}" method="POST" id="formPagamento">
        @csrf
        <!-- Campo de Descrição -->
        <div class="row mb-3">
            <label for="descricao" class="col-sm-2 col-form-label">Descrição</label>
            <div class="col-sm-10">
                <input type="text" class="form-control @error('descricao')is-invalid @enderror" id="descricao"
                    name="descricao" value="{{ old('descricao') }}">
                @error('descricao')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- Campo de tipo do cadastro -->
        <div class="row mb-3">
            <label for="tipo" class="col-sm-2 col-form-label">Tipo Pagamento</label>
            <div class="col-sm-10">
                <select class="form-select @error('tipo')is-invalid @enderror" id="tipo" name="tipo">
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

        <!-- Campos normal -->
        <div id="nenhumFields">
            <!-- Campo de Valor -->
            <div class="row mb-3">
                <label for="valor" class="col-sm-2 col-form-label" id="labelValor">Valor</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control @error('valor')is-invalid @enderror" id="valor" name="valor"
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
                    <input type="date" name="data" id="data" class="form-control @error('data')is-invalid @enderror"
                        value="{{ old('data') }}">
                    @error('data')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Campos adicionais para lançamentos recorrentes -->
        <div id="recorrenteFields" style="display: none;">
            <div class="row mb-3">
                <label for="frequencia" class="col-sm-2 col-form-label">Frequência</label>
                <div class="col-sm-10">
                    <select class="form-select @error('frequencia')is-invalid @enderror" id="frequencia"
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
                        class="form-control @error('data_fim')is-invalid @enderror" value="{{ old('data_fim') }}">
                    @error('data_fim')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Campos adicionais para lançamentos Parcelas -->
        <div id="parcelasFields" style="display: none;">
            <!-- Campo de Valor Total -->
            <div class="row mb-3">
                <label for="valorTotal" class="col-sm-2 col-form-label" id="labelValor">Valor Total</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control @error('valorTotal')is-invalid @enderror" id="valorTotal"
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
                        class="form-control @error('dataVencPar')is-invalid @enderror" value="{{ old('dataVencPar') }}">
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
                    <input type="text" class="form-control @error('valorEntrada')is-invalid @enderror" id="valorEntrada"
                        name="valorEntrada" value="{{ old('valorEntrada') }}">
                    @error('valorEntrada')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <!-- Campo de qtd parcelas -->
            <div class="row mb-3">
                <label for="qtdParcelas" class="col-sm-2 col-form-label" id="labelQtdParcelas">Quantidade
                    Parcelas</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control @error('qtdParcelas')is-invalid @enderror" id="qtdParcelas"
                        name="qtdParcelas" value="{{ old('qtdParcelas') }}">
                    @error('qtdParcelas')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <label for="categoria" class="col-sm-2 col-form-label">Categoria</label>
            <div class="col-sm-10">
                <select class="form-select @error('categoria_id') is-invalid @enderror selectpicker" id="categoria"
                    name="categoria_id" data-live-search="true" style="width: 100%;">
                    <option value="">Selecione uma categoria</option>
                    @php
                        $numeroCategoriaPai = 1; // Inicializa a numeração das categorias pai
                    @endphp
                    @foreach ($categorias as $categoria)
                                        <!-- Categoria Pai -->
                                        <option value="{{ $categoria->id }}">{{ $numeroCategoriaPai }} - {{ $categoria->descricao }}
                                        </option>
                                        <!-- Subcategorias -->
                                        @if ($categoria->subcategorias->count() > 0)
                                                        @php
                                                            $numeroSubcategoria = 1; // Inicializa a numeração das subcategorias
                                                        @endphp
                                                        @foreach ($categoria->subcategorias as $subcategoria)
                                                                    <option value="{{ $subcategoria->id }}">-- {{ $numeroCategoriaPai }}.{{ $numeroSubcategoria }} -
                                                                        {{ $subcategoria->descricao }}
                                                                    </option>
                                                                    @php
                                                                        $numeroSubcategoria++; // Incrementa o número da subcategoria
                                                                    @endphp
                                                        @endforeach
                                        @endif
                                        @php
                                            $numeroCategoriaPai++; // Incrementa o número da categoria pai
                                        @endphp
                    @endforeach
                </select>
            </div>
            @error('categoria_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <!-- Campo de Favorecido -->
        <div class="row mb-3">
            <label for="fornecedor_cliente" class="col-sm-2 col-form-label">Fornecedor/Cliente</label>
            <div class="col-sm-10">
                <select class="form-select @error('fornecedor_cliente_id') is-invalid @enderror" id="fornecedor_cliente"
                    name="fornecedor_cliente_id" style="width: 100%;">
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
<script src="{{ asset('js/select2.full.min.js') }}"></script>
<script src="{{asset("js/jquery.mask.min.js")}}"></script>
<script>
    $(document).ready(function () {
        $('#categoria').select2({
            theme: "bootstrap-5",
        });
        $('#fornecedor_cliente').select2({
            theme: "bootstrap-5",
        });
        function selecionaCampos() {
            if ($("#tipo").val() == 0) {
                $("#nenhumFields").show();
                $("#recorrenteFields").hide();
                $("#parcelasFields").hide();
                // Resetando campos recorrentes e parcelas
                $('#frequencia').val('');
                $('#data_fim').val('');
            } else if ($("#tipo").val() == 1) {
                $("#nenhumFields").hide();
                $("#recorrenteFields").hide();
                $("#parcelasFields").show();
                // Resetando campos recorrentes
                $('#frequencia').val('');
                $('#data_fim').val('');
            } else if ($("#tipo").val() == 2) {
                $("#nenhumFields").show();
                $("#recorrenteFields").show();
                $("#parcelasFields").hide();
            }
        }

        selecionaCampos();

        $('#valor').mask('000.000.000.000.000,00', {
            reverse: true
        });
        $('#valorTotal').mask('000.000.000.000.000,00', {
            reverse: true
        });
        $('#valorEntrada').mask('000.000.000.000.000,00', {
            reverse: true
        });

        $("#tipo").change(function () {
            selecionaCampos();
        });
    });
</script>
@endsection