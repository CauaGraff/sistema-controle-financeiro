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

    <form action="{{ route('lancamentos.pagamentos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Campo de Descrição -->
        <div class="row mb-3">
            <label for="descricao" class="col-sm-2 col-form-label">Descrição</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="descricao" name="descricao" required>
            </div>
        </div>

        <!-- Campo de Valor -->
        <div class="row mb-3">
            <label for="valor" class="col-sm-2 col-form-label">Valor</label>
            <div class="col-sm-10">
                <input type="number" class="form-control" id="valor" name="valor" step="0.01" required>
            </div>
        </div>

        <!-- Campo de Data de Vencimento -->
        <div class="row mb-3">
            <label for="data_vencimento" class="col-sm-2 col-form-label">Data de Vencimento</label>
            <div class="col-sm-10">
                <input type="date" class="form-control" id="data_vencimento" name="data_vencimento" required>
            </div>
        </div>

        <!-- Campo de Categoria -->
        <div class="row mb-3">
            <label for="categoria" class="col-sm-2 col-form-label">Categoria</label>
            <div class="col-sm-10">
                <select class="form-select" id="categoria" name="categoria_id" required>
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
            <label for="favorecido" class="col-sm-2 col-form-label">Favorecido</label>
            <div class="col-sm-10">
                <select class="form-select" id="favorecido" name="favorecido_id" required>
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

        <!-- Campo para perguntar se é recorrente -->
        <div class="row mb-3">
            <label for="recorrente" class="col-sm-2 col-form-label">Lançamento Recorrente?</label>
            <div class="col-sm-10">
                <input id="recorrente" name="recorrente" type="checkbox" class="form-check-input">
            </div>
        </div>

        <!-- Campos adicionais para lançamentos recorrentes -->
        <div id="recorrenteFields" style="display: none;">
            <div class="row mb-3">
                <label for="frequencia" class="col-sm-2 col-form-label">Frequência</label>
                <div class="col-sm-10">
                    <select class="form-select" id="frequencia" name="frequencia">
                        <option value="mensal">Mensal</option>
                        <option value="bimestral">Bimestral</option>
                        <option value="trimestral">Trimestral</option>
                        <option value="anual">Anual</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label for="qtd_parcelas" class="col-sm-2 col-form-label">Quantidade de Parcelas</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" id="qtd_parcelas" name="qtd_parcelas">
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Cadastrar Lançamento</button>
    </form>
</div>
@endsection

@section('js')
<script>
    // Mostrar ou esconder campos de lançamento recorrente
    document.getElementById('recorrente').addEventListener('change', function () {
        var recorrenteFields = document.getElementById('recorrenteFields');
        recorrenteFields.style.display = this.checked ? 'block' : 'none';
    });
</script>
@endsection