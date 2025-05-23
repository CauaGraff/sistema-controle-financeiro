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

    <form action="{{ route('lancamentos.update', $lancamento->id) }}" method="POST" enctype="multipart/form-data">
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
                    value="{{ old('data', \Carbon\Carbon::parse($lancamento->data_venc)->format('Y-m-d')) }}" {{ $lancamentoBaixa ? 'disabled' : '' }}>
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
                <select class="form-select selectpicker" id="categoria" name="categoria_id" data-live-search="true"
                    style="width: 100%;">
                    <option value="">Selecione uma categoria</option>
                    @php
                        function listarCategorias($categorias, $prefixo = '', $nivel = 0, $categoriaSelecionada = null)
                        {
                            $contador = 1;
                            foreach ($categorias as $categoria) {
                                // Número hierárquico da categoria
                                $numAtual = $prefixo ? "$prefixo.$contador" : (string) $contador;

                                // Verifica se essa categoria é a que está selecionada
                                $selected = $categoriaSelecionada == $categoria->id ? 'selected' : '';

                                echo '<option value="' . $categoria->id . '" ' . $selected . '>' . $numAtual . ' - ' . $categoria->descricao . '</option>';

                                // Chama a função recursiva apenas se houver subcategorias
                                if ($categoria->subcategorias->isNotEmpty()) {
                                    listarCategorias($categoria->subcategorias, $numAtual, $nivel + 1, $categoriaSelecionada);
                                }
                                $contador++;
                            }
                        }

                        // Buscar apenas categorias principais (onde id_categoria_pai é NULL)
                        $categoriasPrincipais = $categorias->whereNull('id_categoria_pai');

                        // Pegando a categoria previamente cadastrada
                        $categoriaSelecionada = old('categoria_id', $pagamento->categoria_id ?? null);

                        listarCategorias($categoriasPrincipais, '', 0, $lancamento->id_categoria);
                    @endphp
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
        @if ($lancamentoBaixa)
            <hr>
            <div class="row">
                <!-- Coluna esquerda para os campos -->
                <div class="col-md-8 border-right">
                    <!-- Data do Pagamento -->
                    <div class="form-group">
                        <label for="data_pagamento">Data do Pagamento:</label>
                        <input type="date" class="form-control" name="data_pagamento" id="data_pagamento"
                            value="{{date('Y-m-d', strtotime($lancamentoBaixa->created_at))}}" disabled>
                    </div>
                    <!-- Linha para Juros, Multa e Desconto -->
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <label class="form-check-label" for="aplicar_multa">Multa:</label>
                            <input type="text" class="form-control mt-2" id="multa" name="multa" placeholder="Multa (R$)"
                                value="{{$lancamentoBaixa->multa}}" disabled>
                        </div>
                        <div class="col-md-4">
                            <label class="form-check-label" for="aplicar_juros">Juros:</label>
                            <input type="text" class="form-control mt-2" id="juros" name="juros" placeholder="Juros (R$)"
                                value="{{$lancamentoBaixa->juros}}" disabled>
                        </div>
                        <div class="col-md-4">
                            <label class="form-check-label" for="aplicar_desconto">Desconto:</label>
                            <input type="text" class="form-control mt-2" id="desconto" name="desconto"
                                placeholder="Desconto (R$)" value="{{$lancamentoBaixa->desconto}}" disabled>
                        </div>
                    </div>
                    <!-- Linha para o número do documento -->
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="valor_pago">Valor Pago:</label>
                                <input type="text" class="form-control" id="valor_pago" name="valor_pago"
                                    placeholder="Valor Pago" value="{{$lancamentoBaixa->valor}}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="numero_documento">Número do Documento:</label>
                                <input type="text" class="form-control" id="numero_documento" name="numero_documento"
                                    value="{{$lancamentoBaixa->doc}}">
                            </div>
                        </div>
                    </div>
                    <!-- Botão para excluir a baixa (se o lançamento estiver baixado) -->
                    <div class="row mt-3 g-3">
                        <div class="col-md-12 d-flex">
                            <a href="{{route('lancamentos.baixa.delete', $lancamento->id)}}"
                                class="btn btn-danger btn-lg w-50 me-2">
                                Excluir Baixa
                            </a>
                            <button type="submit" class="btn btn-warning btn-lg w-50">
                                Atualizar
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Coluna direita para o anexo -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="anexo">Documento Anexado:</label>
                        @if($lancamentoBaixa->anexo)
                            <div class="d-flex justify-content-between mt-2 mb-2">
                                <a href="{{ getenv("APP_URL") . \Illuminate\Support\Facades\Storage::url($lancamentoBaixa->anexo) }}"
                                    class="btn btn-secondary" target="_blank" style="flex: 1; margin-right: 5px;">
                                    <i class="fa-solid fa-eye"></i> Visualizar
                                </a>
                                <a href="{{ route('lancamentos.anexo.delete', $lancamentoBaixa->id) }}" class="btn btn-danger"
                                    style="flex: 1; margin-left: 5px;">
                                    <i class="fa-solid fa-trash"></i> Excluir
                                </a>
                            </div>
                            <iframe src="{{getenv("APP_URL") . \Illuminate\Support\Facades\Storage::url($lancamentoBaixa->anexo) }}"
                                style="width: 100%; height: 275px;"></iframe>
                        @else
                            <input type="file" class="form-control" id="anexo" name="anexo" accept="application/pdf,image/*">
                        @endif
                    </div>
                </div>
            </div>
        @endif
        @if (!$lancamentoBaixa)
            <div class="row mt-4">
                <div class="d-flex">
                    <button type="submit" class="btn btn-warning btn-lg w-50 me-2">Atualizar</button>

                    <a href="{{ $lancamento->tipo == 'P' ? route('lancamentos.pagamentos.baixa', $lancamento->id) : route('lancamentos.recebimentos.baixa', $lancamento->id) }}"
                        class="btn btn-success btn-lg w-50">
                        Pagar
                    </a>

                </div>
            </div>
        @endif
    </form>

@endsection

@section('js')
    <script src="{{ asset('js/jquery.mask.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#valor, #multa, #juros, #desconto, #valor_pago').mask('000.000.000.000.000,00', {
                reverse: true
            });
        });
    </script>
@endsection