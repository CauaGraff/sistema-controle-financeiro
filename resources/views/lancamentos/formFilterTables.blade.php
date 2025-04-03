<!-- Filtro lateral -->
<div id="filter-side">
    <button class="close-btn" id="close-filter"><i class="fa-solid fa-xmark"></i></button>
    <form method="GET"
        action="{{ $route == 'P' ? route('lancamentos.pagamentos.index') : route('lancamentos.recebimentos.index') }}"
        id="form-filter">
        <h4 class="mb-4">Filtros</h4>
        <div class="form-row">
            <div class="form-group col-md-12">
                <label for="data_inicio">Data Início</label>
                <input type="date" name="data_inicio" id="data_inicio" class="form-control"
                    value="{{ request('data_inicio') ?: \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}">
            </div>
            <div class="form-group col-md-12 mt-1">
                <label for="data_fim">Data Fim</label>
                <input type="date" name="data_fim" id="data_fim" class="form-control"
                    value="{{ request('data_fim') ?: \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d') }}">
            </div>
            <div class="form-group col-md-12 mt-1">
                <label for="valor_min">Valor Mínimo</label>
                <input type="text" name="valor_min" id="valor_min" class="form-control" placeholder="Valor Mínimo"
                    value="{{ request('valor_min') }}">
            </div>
            <div class="form-group col-md-12 mt-1">
                <label for="valor_max">Valor Máximo</label>
                <input type="text" name="valor_max" id="valor_max" class="form-control" placeholder="Valor Máximo"
                    value="{{ request('valor_max') }}">
            </div>
            <div class="form-group col-md-12 mt-1">
                <label for="categoria">Categoria:</label>
                <select class="form-select selectpicker" id="categoria" name="categoria_id" data-live-search="true"
                    style="width: 100%;">
                    <option value="">Selecione uma categoria</option>

                    @php
                        function listarCategorias($categorias, $prefixo = '', $nivel = 0)
                        {
                            $contador = 1;
                            foreach ($categorias as $categoria) {
                                // Número hierárquico da categoria
                                $numAtual = $prefixo ? "$prefixo.$contador" : (string) $contador;

                                echo '<option value="' . $categoria->id . '">' . $numAtual . ' - ' . $categoria->descricao . '</option>';

                                // Chama a função recursiva apenas se houver subcategorias
                                if ($categoria->subcategorias->isNotEmpty()) {
                                    listarCategorias($categoria->subcategorias, $numAtual, $nivel + 1);
                                }

                                $contador++;
                            }
                        }

                        // Buscar apenas categorias principais (onde id_categoria_pai é NULL)
                        $categoriasPrincipais = $categorias->whereNull('id_categoria_pai');
                        listarCategorias($categoriasPrincipais);
                    @endphp
                </select>

                @error('categoria_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group col-md-12 mt-1">
                <label for="fornecedor_cliente_id">Fornecedor/Cliente</label>
                <select name="fornecedor_cliente_id" id="fornecedor_cliente_id" class="form-select">
                    <option value="">Selecione Fornecedor/Cliente</option>
                    @foreach($fornecedoresClientes as $fornecedor)
                        <option value="{{ $fornecedor->id }}" @if(request('fornecedor_cliente_id') == $fornecedor->id)
                        selected @endif>{{ $fornecedor->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-12 mt-1">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">Selecione o Status</option>
                    <option value="pago" @if(request('status') == 'pago') selected @endif>Pago</option>
                    <option value="em_aberto" @if(request('status') == 'em_aberto') selected @endif>Em Aberto</option>
                    <option value="vencido" @if(request('status') == 'vencido') selected @endif>Vencido</option>
                </select>
            </div>
            <input type="hidden" name="route" value="{{ $route }}">
            <button type="submit" class="btn btn-primary btn-block mt-3">Aplicar Filtros</button>
            <button type="reset" class="btn btn-wharing btn-block mt-3">Limpar</button>
        </div>
    </form>
</div>