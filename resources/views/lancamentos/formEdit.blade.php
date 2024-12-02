@extends('_theme')

@section('content')
@if ($lancamento->tipo == "R")
    <h1>Cadastrar Recebimentos</h1>
@else
    <h1>Cadastrar Pagamentos</h1>
@endif
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form action="{{ route('lancamentos.update', $lancamento->id) }}">
    @csrf
    @method('PUT')
    <!-- Campo de Descrição -->
    <div class="row mb-3">
        <label for="descricao" class="col-sm-2 col-form-label">Descrição</label>
        <div class="col-sm-10">
            <input type="text" class="form-control @error('descricao')is-invalid @enderror" id="descricao"
                name="descricao" value="{{ old('descricao', $lancamento->descricao) }}">
            @error('descricao')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <!-- Campo de tipo do cadastro
    <div class="row mb-3">
        <label for="tipo" class="col-sm-2 col-form-label">Tipo</label>
        <div class="col-sm-10">
            <select class="form-select @error('tipo')is-invalid @enderror" id="tipo" name="tipo">
                <option value="0" {{ old('tipo') == 0 ? 'selected' : '' }}>Nenhum</option>
                <option value="1" {{ old('tipo') == 1 ? 'selected' : '' }}>Parcelas</option>
                <option value="2" {{ old('tipo') == 2 ? 'selected' : '' }}>Recorrente</option>
            </select>
            @error('tipo')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div> -->
    <!-- Campos normal -->

    <!-- Campo de Valor -->
    <div class="row mb-3">
        <label for="valor" class="col-sm-2 col-form-label" id="labelValor">Valor</label>
        <div class="col-sm-10">
            <input type="text" class="form-control @error('valor')is-invalid @enderror" id="valor" name="valor"
                value="{{ old('valor', $lancamento->valor) }}">
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
                value="{{ old('data', $lancamento->data_venc) }}">
            @error('data')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <!-- Campo de Categoria -->
    <div class="row mb-3">
        <label for="categoria" class="col-sm-2 col-form-label">Categoria</label>
        <div class="col-sm-10">
            <select class="form-select @error('categoria_id')is-invalid @enderror" id="categoria" name="categoria_id">
                <option value="">Selecione uma categoria</option>
                @foreach ($categoriasAgrupadas as $grupo)
                    @if (isset($grupo['categoria']))
                        <optgroup label="{{ $grupo['categoria']->descricao }}">
                            @foreach ($grupo['subcategorias'] as $subcategoria)
                                <option value="{{ $subcategoria->id }}" {{ old('categoria_id', $lancamento->id_categoria) == $subcategoria->id ? 'selected' : '' }}>
                                    {{ $subcategoria->descricao }}
                                </option>
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
    <!-- Campo de Favorecido -->
    <div class="row mb-3">
        <label for="fornecedor_cliente" class="col-sm-2 col-form-label">Fornecedor/Cleinte</label>
        <div class="col-sm-10">
            <select class="form-select @error('fornecedor_cliente_id')is-invalid @enderror" id="fornecedor_cliente"
                name="fornecedor_cliente_id">
                <option value="">Selecione o Fornecedor/Cliente</option>
                @foreach ($fornecedores as $fornecedor)
                    <option value="{{ $fornecedor->id }}" {{ old('fornecedor_cliente_id', $lancamento->id_fornecedor_cliente) == $fornecedor->id ? 'selected' : '' }}>
                        {{ $fornecedor->nome }}
                    </option>
                @endforeach
                @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}" {{ old('fornecedor_cliente_id', $lancamento->id_fornecedor_cliente) == $fornecedor->id ? 'selected' : '' }}>
                        {{ $cliente->nome }}
                    </option>
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