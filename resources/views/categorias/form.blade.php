<div class="mb-3">
    <label for="descricao" class="form-label">Descrição</label>
    <input type="text" class="form-control" id="descricao" name="descricao" value="{{ old('descricao') }}">
    @error('descricao')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
<div class="mb-3">
    <label for="id_categoria_pai" class="form-label">Categoria Pai (Opcional)</label>
    <select class="form-select" id="id_categoria_pai" name="id_categoria_pai">
        <option value="">Nenhuma</option>
        @foreach ($categorias as $categoriaPai)
            <option value="{{ $categoriaPai->id }}" {{ isset($categoria) && $categoria->id_categoria_pai == $categoriaPai->id ? 'selected' : '' }}>
                {{ $categoriaPai->descricao }}
            </option>
        @endforeach
    </select>
</div>