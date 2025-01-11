<div class="mb-4">
    <label for="descricao" class="form-label">Descrição</label>
    <input type="text" class="form-control @error('descricao') is-invalid @enderror" id="descricao" name="descricao" value="{{ old('descricao') }}" placeholder="Digite a descrição do lançamento">
    @error('descricao')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @enderror
</div>

<div class="mb-4">
    <label for="id_categoria_pai" class="form-label">Categoria Pai (Opcional)</label>
    <select class="form-select @error('id_categoria_pai') is-invalid @enderror" id="id_categoria_pai" name="id_categoria_pai">
        <option value="">Nenhuma</option>
        @foreach ($categorias as $categoriaPai)
        <option value="{{ $categoriaPai->id }}" {{ isset($categoria) && $categoria->id_categoria_pai == $categoriaPai->id ? 'selected' : '' }}>
            {{ $categoriaPai->descricao }}
        </option>
        @endforeach
    </select>
    @error('id_categoria_pai')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @enderror
</div>