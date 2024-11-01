<div class="mb-3">
    <label for="cnpj_cpf" class="form-label">CNPJ/CPF</label>
    <input type="text" class="form-control" id="cnpj_cpf" name="cnpj_cpf"
        value="{{ old('cnpj_cpf', $favorecido->cnpj_cpf ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="telefone" class="form-label">Telefone</label>
    <input type="text" class="form-control" id="telefone" name="telefone"
        value="{{ old('telefone', $favorecido->telefone ?? '') }}">
</div>
<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" class="form-control" id="email" name="email"
        value="{{ old('email', $favorecido->email ?? '') }}">
</div>
<div class="mb-3">
    <label for="cep" class="form-label">CEP</label>
    <input type="text" class="form-control" id="cep" name="cep" value="{{ old('cep', $favorecido->cep ?? '') }}"
        required>
</div>
<div class="mb-3">
    <label for="uf" class="form-label">UF</label>
    <input type="text" class="form-control" id="uf" name="uf" value="{{ old('uf', $favorecido->uf ?? '') }}" required
        maxlength="2">
</div>
<div class="mb-3">
    <label for="cidade" class="form-label">Cidade</label>
    <input type="text" class="form-control" id="cidade" name="cidade"
        value="{{ old('cidade', $favorecido->cidade ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="bairro" class="form-label">Bairro</label>
    <input type="text" class="form-control" id="bairro" name="bairro"
        value="{{ old('bairro', $favorecido->bairro ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="rua" class="form-label">Rua</label>
    <input type="text" class="form-control" id="rua" name="rua" value="{{ old('rua', $favorecido->rua ?? '') }}"
        required>
</div>
<div class="mb-3">
    <label for="complemento" class="form-label">Complemento</label>
    <input type="text" class="form-control" id="complemento" name="complemento"
        value="{{ old('complemento', $favorecido->complemento ?? '') }}">
</div>
<div class="mb-3">
    <label for="tipo" class="form-label">Tipo</label>
    <select class="form-control" id="tipo" name="tipo" required>
        <option value="F" {{ (isset($favorecido) && $favorecido->tipo == 'F') ? 'selected' : '' }}>Fornecedor</option>
        <option value="C" {{ (isset($favorecido) && $favorecido->tipo == 'C') ? 'selected' : '' }}>Cliente</option>
    </select>
</div>