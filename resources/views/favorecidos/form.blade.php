<div class="row mb-3">
    <div class="col-md-4">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome"
            value="{{ old('nome', $favorecido->nome ?? '') }}">
        @error('nome')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="cnpj_cpf" class="form-label">CNPJ/CPF</label>
        <input type="text" class="form-control @error('cnpj_cpf') is-invalid @enderror" id="cnpj_cpf"
            name="cnpj_cpf" value="{{ old('cnpj_cpf', $favorecido->cnpj_cpf ?? '') }}">
        @error('cnpj_cpf')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="telefone" class="form-label">Telefone</label>
        <input type="text" class="form-control @error('telefone') is-invalid @enderror" id="telefone"
            name="telefone" value="{{ old('telefone', $favorecido->telefone ?? '') }}">
        @error('telefone')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-4">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
            value="{{ old('email', $favorecido->email ?? '') }}">
        @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="cep" class="form-label">CEP</label>
        <input type="text" class="form-control @error('cep') is-invalid @enderror" id="cep" name="cep"
            value="{{ old('cep', $favorecido->cep ?? '') }}">
        @error('cep')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="uf" class="form-label">UF</label>
        <input type="text" class="form-control @error('uf') is-invalid @enderror" id="uf" name="uf"
            value="{{ old('uf', $favorecido->uf ?? '') }}" maxlength="2">
        @error('uf')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-4">
        <label for="cidade" class="form-label">Cidade</label>
        <input type="text" class="form-control @error('cidade') is-invalid @enderror" id="cidade" name="cidade"
            value="{{ old('cidade', $favorecido->cidade ?? '') }}">
        @error('cidade')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="bairro" class="form-label">Bairro</label>
        <input type="text" class="form-control @error('bairro') is-invalid @enderror" id="bairro" name="bairro"
            value="{{ old('bairro', $favorecido->bairro ?? '') }}">
        @error('bairro')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="rua" class="form-label">Rua</label>
        <input type="text" class="form-control @error('rua') is-invalid @enderror" id="rua" name="rua"
            value="{{ old('rua', $favorecido->rua ?? '') }}">
        @error('rua')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-12">
        <label for="complemento" class="form-label">Complemento</label>
        <input type="text" class="form-control @error('complemento') is-invalid @enderror" id="complemento"
            name="complemento" value="{{ old('complemento', $favorecido->complemento ?? '') }}">
        @error('complemento')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-15">
        <label for="tipo" class="form-label">Tipo</label>
        <select class="form-select @error('tipo') is-invalid @enderror" id="tipo" name="tipo">
            <option value="F" {{ (isset($favorecido) && $favorecido->tipo == 'F') ? 'selected' : '' }}>Fornecedor</option>
            <option value="C" {{ (isset($favorecido) && $favorecido->tipo == 'C') ? 'selected' : '' }}>Cliente</option>
        </select>
        @error('tipo')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>