<!-- Filtro lateral -->
<div id="filter-side">
    <button class="close-btn" id="close-filter">&times;</button>
    <form method="GET" action="{{ $route == 'P' ? route('lancamentos.pagamentos.index') : route('lancamentos.recebimentos.index') }}">
        <h4 class="mb-4">Filtros</h4>
        <div class="form-row">
            <div class="form-group col-md-12">
                <label for="data_inicio">Data Início</label>
                <input type="date" name="data_inicio" class="form-control" value="{{ request('data_inicio') }}">
            </div>
            <div class="form-group col-md-12">
                <label for="data_fim">Data Fim</label>
                <input type="date" name="data_fim" class="form-control" value="{{ request('data_fim') }}">
            </div>
            <div class="form-group col-md-12">
                <label for="valor_min">Valor Mínimo</label>
                <input type="text" name="valor_min" class="form-control" placeholder="Valor Mínimo" value="{{ request('valor_min') }}">
            </div>
            <div class="form-group col-md-12">
                <label for="valor_max">Valor Máximo</label>
                <input type="text" name="valor_max" class="form-control" placeholder="Valor Máximo" value="{{ request('valor_max') }}">
            </div>
            <div class="form-group col-md-12">
                <label for="categoria_id">Categoria</label>
                <select name="categoria_id" class="form-control">
                    <option value="">Selecione a Categoria</option>
                    @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" @if(request('categoria_id')==$categoria->id) selected @endif>{{ $categoria->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-12">
                <label for="fornecedor_cliente_id">Fornecedor/Cliente</label>
                <select name="fornecedor_cliente_id" class="form-control">
                    <option value="">Selecione Fornecedor/Cliente</option>
                    @foreach($fornecedoresClientes as $fornecedor)
                    <option value="{{ $fornecedor->id }}" @if(request('fornecedor_cliente_id')==$fornecedor->id) selected @endif>{{ $fornecedor->nome }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block mt-3">Aplicar Filtros</button>
        </div>
    </form>
</div>