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
                <label for="categoria_id">Categoria</label>
                <select class="form-select @error('categoria_id')is-invalid @enderror selectpicker" id="categoria_id"
                    name="categoria_id" data-live-search="true">
                    <option value="">Selecione uma categoria</option>
                    @foreach ($categoriasAgrupadas as $grupo)
                        @if (isset($grupo['categoria']))
                            <optgroup label="{{ $grupo['categoria']->descricao }}">
                                <!-- Adiciona a categoria pai como a primeira opção -->
                                <option value="{{ $grupo['categoria']->id }}" {{ old('categoria_id') == $grupo['categoria']->id ? 'selected' : '' }}>
                                    {{ $grupo['categoria']->descricao }}
                                </option>

                                <!-- Depois, adiciona as subcategorias -->
                                @foreach ($grupo['subcategorias'] as $subcategoria)
                                    <option value="{{ $subcategoria->id }}" {{ old('categoria_id') == $subcategoria->id ? 'selected' : '' }}>
                                        {{ $subcategoria->descricao }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endif
                    @endforeach

                </select>
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