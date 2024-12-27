@extends('_theme')

@section('content')
@if ($lancamento->tipo == "R")
    <h1>Editar Recebimento</h1>
@else
    <h1>Editar Pagamento</h1>
@endif

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Verifica se o lançamento está baixado -->
@if ($lancamentoBaixa)
    <div class="alert alert-warning" role="alert">
        Este lançamento já foi baixado. Você pode editar apenas os campos permitidos.
    </div>
@endif

<form action="{{ route('lancamentos.update', $lancamento->id) }}" method="POST">
    @method('PUT')
    @csrf
    <!-- Campo de Descrição -->
    <div class="row mb-3">
        <label for="descricao" class="col-sm-2 col-form-label">Descrição</label>
        <div class="col-sm-10">
            <input type="text" class="form-control @error('descricao')is-invalid @enderror" id="descricao"
                name="descricao" value="{{ old('descricao', $lancamento->descricao) }}" {{ $lancamentoBaixa ? '' : '' }}>
            @error('descricao')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    <!-- Campo de Valor (somente leitura se baixado) -->
    <div class="row mb-3">
        <label for="valor" class="col-sm-2 col-form-label" id="labelValor">Valor</label>
        <div class="col-sm-10">
            <input type="text" class="form-control @error('valor')is-invalid @enderror" id="valor" name="valor"
                value="{{ old('valor', $lancamento->valor) }}" {{ $lancamentoBaixa ? 'disabled' : '' }}>
            @error('valor')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    <!-- Campo de Data de Vencimento (somente leitura se baixado) -->
    <div class="row mb-3">
        <label for="data" class="col-sm-2 col-form-label" id="labelDataVenc">Data de Vencimento</label>
        <div class="col-sm-10">
            <input type="date" name="data" id="data" class="form-control @error('data')is-invalid @enderror"
                value="{{ old('data', $lancamento->data_venc) }}" {{ $lancamentoBaixa ? 'disabled' : '' }}>
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
            <select class="form-select @error('categoria_id')is-invalid @enderror" id="categoria" name="categoria_id" {{ $lancamentoBaixa ? '' : '' }}>
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

    <!-- Campo de Fornecedor/Cliente -->
    <div class="row mb-3">
        <label for="fornecedor_cliente" class="col-sm-2 col-form-label">Fornecedor/Cliente</label>
        <div class="col-sm-10">
            <select class="form-select @error('fornecedor_cliente_id')is-invalid @enderror" id="fornecedor_cliente"
                name="fornecedor_cliente_id" {{ $lancamentoBaixa ? '' : '' }}>
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
    <hr>

    <div class="col-md-6">
        <div class="form-group">
            <label for="data_pagamento">Data do Pagamento:</label>
            <input type="date" class="form-control" name="data_pagamento" id="data_pagamento"
                value="{{date('Y-m-d', strtotime($lancamentoBaixa->created_at))}}" disabled>
        </div>
    </div>
    </div>

    <!-- Linha para Juros, Multa e Desconto -->
    <div class="row mt-2">
        <div class="col-md-2">
            <label class="form-check-label" for="aplicar_multa">Multa:</label>
            <input type="text" class="form-control mt-2" id="multa" name="multa" placeholder="Multa (R$)"
                value="{{$lancamentoBaixa->multa}}" disabled>
        </div>
        <div class="col-md-2">
            <label class="form-check-label" for="aplicar_juros">Juros:</label>
            <input type="text" class="form-control mt-2" id="juros" name="juros" placeholder="Juros (R$)"
                value="{{$lancamentoBaixa->juros}}" disabled>
        </div>
        <div class="col-md-2">
            <label class="form-check-label" for="aplicar_desconto">Desconto:</label>
            <input type="text" class="form-control mt-2" id="desconto" name="desconto" placeholder="Desconto (R$)"
                value="{{$lancamentoBaixa->desconto}}" disabled>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-check-label" for="valor_pago">Valor Pago:</label>
                <input type="text" class="form-control mt-2" id="valor_pago" name="valor_pago" placeholder="Valor Pago"
                    value="{{$lancamentoBaixa->valor}}" disabled>
            </div>
        </div>
    </div>

    <!-- Linha para o número do documento -->
    <div class="row mt-2">
        <div class="col-md-3">
            <div class="form-group">
                <label for="numero_documento">Número do Documento:</label>
                <input type="text" class="form-control" id="numero_documento" name="numero_documento"
                    value="{{$lancamentoBaixa->numero_documento}}">

            </div>
        </div>
        <div class="col-md-9">
            <div class="form-group">
                <label for="anexo">Anexar Documento:</label>
                <input type="file" class="form-control" id="anexo" name="anexo" accept="application/pdf,image/*">
            </div>
        </div>
    </div>

    <!-- Botão para excluir a baixa (se o lançamento estiver baixado) -->
    @if ($lancamentoBaixa)
            <div class="row mb-3">
                <div class="col-sm-10 offset-sm-2">
                    <!-- <form action="{{ route('lancamentos.baixa.delete', $lancamento->id) }}" method="POST">
        @csrf
        @method('DELETE')
        </form> -->
                    <a href="{{route('lancamentos.baixa.delete', $lancamento->id)}}" type="submit"
                        class="btn btn-danger">Excluir Baixa</a>
                </div>
            </div>
    @endif

    <button type="submit" class="btn btn-primary" {{ $lancamentoBaixa ? 'disabled' : '' }}>Atualizar</button>
</form>

@endsection

@section('js')
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#valor, #multa, #juros, #desconto, #valor_pago').mask('000.000.000.000.000,00', { reverse: true });
    });
</script>
@endsection