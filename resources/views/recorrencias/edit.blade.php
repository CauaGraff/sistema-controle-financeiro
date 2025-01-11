@extends('_theme')

@section('content')
<h1>Editar Recorrência</h1>

<form action="{{ route('recorrencias.update', $recorrencia->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-group mb-3">
                    <label for="descricao" class="form-label">Descrição</label>
                    <input type="text" name="descricao" id="descricao" value="{{ old('descricao', $recorrencia->descricao) }}" class="form-control">
                </div>

                <div class="form-group mb-3">
                    <label for="data_inicio" class="form-label">Data Início</label>
                    <input type="date" name="data_inicio" id="data_inicio" value="{{ old('data_inicio', $recorrencia->data_inicio) }}" class="form-control">
                </div>

                <div class="form-group mb-3">
                    <label for="data_fim" class="form-label">Data Fim</label>
                    <input type="date" name="data_fim" id="data_fim" value="{{ old('data_fim', $recorrencia->data_fim ? $recorrencia->data_fim : '') }}" class="form-control">
                </div>

                <div class="form-group mb-3">
                    <label for="valor" class="form-label">Valor</label>
                    <input type="number" name="valor" id="valor" value="{{ old('valor', $recorrencia->valor) }}" class="form-control">
                </div>

                <div class="form-group mb-3">
                    <label for="tipo_recorrencia" class="form-label">Tipo de Recorrência</label>
                    <select name="tipo_recorrencia" id="tipo_recorrencia" class="form-control">
                        <option value="diaria" {{ old('tipo_recorrencia', $recorrencia->tipo_recorrencia) == 'diaria' ? 'selected' : '' }}>Diária</option>
                        <option value="semanal" {{ old('tipo_recorrencia', $recorrencia->tipo_recorrencia) == 'semanal' ? 'selected' : '' }}>Semanal</option>
                        <option value="mensal" {{ old('tipo_recorrencia', $recorrencia->tipo_recorrencia) == 'mensal' ? 'selected' : '' }}>Mensal</option>
                        <option value="anual" {{ old('tipo_recorrencia', $recorrencia->tipo_recorrencia) == 'anual' ? 'selected' : '' }}>Anual</option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary w-100">Atualizar</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection