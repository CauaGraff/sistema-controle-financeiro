@extends('_theme')

@section('title', 'Cadastrar Fornecedor/Cliente')

@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-md-8">
            <h1 class="text-center mb-4">Cadastrar Novo Fornecedor/Cliente</h1>
            <div class="card shadow-sm p-4">
                <form action="{{ route('favorecidos.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome"
                                name="nome" value="{{ old('nome', $favorecido->nome ?? '') }}">
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
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email', $favorecido->email ?? '') }}">
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
                            <input type="text" class="form-control @error('cidade') is-invalid @enderror" id="cidade"
                                name="cidade" value="{{ old('cidade', $favorecido->cidade ?? '') }}">
                            @error('cidade')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="bairro" class="form-label">Bairro</label>
                            <input type="text" class="form-control @error('bairro') is-invalid @enderror" id="bairro"
                                name="bairro" value="{{ old('bairro', $favorecido->bairro ?? '') }}">
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
                            <input type="text" class="form-control @error('complemento') is-invalid @enderror"
                                id="complemento" name="complemento"
                                value="{{ old('complemento', $favorecido->complemento ?? '') }}">
                            @error('complemento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-15">
                            <label for="tipo" class="form-label">Tipo</label>
                            <select class="form-select @error('tipo') is-invalid @enderror" id="tipo" name="tipo">
                                <option value="F" {{ (isset($favorecido) && $favorecido->tipo == 'F') ? 'selected' : '' }}>
                                    Fornecedor</option>
                                <option value="C" {{ (isset($favorecido) && $favorecido->tipo == 'C') ? 'selected' : '' }}>
                                    Cliente
                                </option>
                            </select>
                            @error('tipo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal de Carregamento -->
    <div class="modal fade" id="modalLoading" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                    <p class="mt-2">Buscando informações do CEP...</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/jquery.mask.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            function aplicarMascaraCnpjCpf() {
                var cnpjCpf = $("#cnpj_cpf").val().replace(/\D/g, ''); // Remove tudo que não for número

                if (cnpjCpf.length < 11) {
                    $("#cnpj_cpf").mask("000.000.000-00"); // Máscara de CPF
                } else {
                    $("#cnpj_cpf").mask("00.000.000/0000-00"); // Máscara de CNPJ
                }
            }

            // Aplica a máscara ao digitar
            $("#cnpj_cpf").on("input", function () {
                aplicarMascaraCnpjCpf();
            });

            // Aplica máscara inicial se já houver valor
            aplicarMascaraCnpjCpf();

            $('#cep').change(function () {
                var cep = $(this).val().replace(/\D/g, '');
                if (cep.length === 8) {
                    $('#modalLoading').modal('show');
                    $.ajax({
                        url: 'https://viacep.com.br/ws/' + cep + '/json/',
                        method: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            $('#modalLoading').modal('hide');
                            if (!data.erro) {
                                $('#cidade').val(data.localidade);
                                $('#uf').val(data.uf);
                                $('#bairro').val(data.bairro);
                                $('#rua').val(data.logradouro);
                                // Força o fechamento do modal após preenchimento
                                setTimeout(function () {
                                    $("#modalLoading").modal('hide');
                                }, 500);
                            } else {
                                alert('CEP não encontrado!');
                            }
                        },
                        error: function () {
                            $('#modalLoading').modal('hide');
                            alert('Erro ao buscar o CEP!');
                        }
                    });
                }
            });

            // Máscara para CEP e Telefone
            $('#cep').mask('00000-000');
            $('#telefone').mask('(00) 00000-0000');

            // Auto preencher os campos de endereço com o CEP
            $("#cep").change(function () {
                var cep = $(this).val().replace(/\D/g, ''); // Remove tudo que não é número
                if (cep.length === 8) {
                    $.ajax({
                        url: 'https://viacep.com.br/ws/' + cep + '/json/',
                        method: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            if (!data.erro) {
                                $('#rua').val(data.logradouro);
                                $('#bairro').val(data.bairro);
                                $('#cidade').val(data.localidade);
                                $('#uf').val(data.uf);

                                // Deixe os campos habilitados caso o usuário queira editar
                                $('#rua, #bairro, #cidade, #uf').prop('disabled', false);
                            } else {
                                alert('CEP não encontrado!');
                            }
                        },
                        error: function () {
                            alert('Erro ao buscar o CEP!');
                        }
                    });
                } else {
                    alert('Por favor, digite um CEP válido.');
                }
            });
        })
    </script>
@endsection