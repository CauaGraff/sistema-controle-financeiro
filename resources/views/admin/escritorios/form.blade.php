@php
    $isEdit = isset($escritorio);
    $route = $isEdit
        ? route('escritorios.update', $escritorio)
        : route('escritorios.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<form action="{{ $route }}" method="POST">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Nome</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $escritorio->name ?? '') }}" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col">
            <label class="form-label">CNPJ</label>
            <input type="text" name="cnpj" class="form-control @error('cnpj') is-invalid @enderror"
                value="{{ old('cnpj', $escritorio->cnpj ?? '') }}">
            @error('cnpj') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-4">
            <label class="form-label">CEP</label>
            <input type="text" name="cep" id="cep" class="form-control @error('cep') is-invalid @enderror"
                value="{{ old('cep', $escritorio->cep ?? '') }}">
            @error('cep') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-4">
            <label class="form-label">UF</label>
            <input type="text" name="uf" class="form-control @error('uf') is-invalid @enderror"
                value="{{ old('uf', $escritorio->uf ?? '') }}">
            @error('uf') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-4">
            <label class="form-label">Complemento</label>
            <input type="text" name="complemento" class="form-control @error('complemento') is-invalid @enderror"
                value="{{ old('complemento', $escritorio->complemento ?? '') }}">
            @error('complemento') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Rua</label>
            <input type="text" name="rua" class="form-control @error('rua') is-invalid @enderror"
                value="{{ old('rua', $escritorio->rua ?? '') }}">
            @error('rua') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col">
            <label class="form-label">Bairro</label>
            <input type="text" name="bairro" class="form-control @error('bairro') is-invalid @enderror"
                value="{{ old('bairro', $escritorio->bairro ?? '') }}">
            @error('bairro') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col">
            <label class="form-label">Cidade</label>
            <input type="text" name="cidade" class="form-control @error('cidade') is-invalid @enderror"
                value="{{ old('cidade', $escritorio->cidade ?? '') }}">
            @error('cidade') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Observações</label>
        <textarea name="obs" rows="3"
            class="form-control @error('obs') is-invalid @enderror">{{ old('obs', $escritorio->obs ?? '') }}</textarea>
        @error('obs') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-check mb-3">
        <input type="checkbox" name="active" value="1" class="form-check-input" {{ old('active', $escritorio->active ?? true) ? 'checked' : '' }}>
        <label class="form-check-label">Ativo</label>
    </div>

    <button class="btn btn-primary w-100">
        {{ $isEdit ? 'Atualizar Escritório' : 'Cadastrar Escritório' }}
    </button>
</form>