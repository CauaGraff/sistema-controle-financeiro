@extends('admin._theme')

@section('title', 'Cadastro de Empresas')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="col-lg-6 col-md-8 col-10">
        <div class="card shadow">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('adm.cadastro.empresas.post') }}">
                    @csrf
                    <div class="row mb-3">
                        <!-- Nome -->
                        <div class="col">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome"
                                name="nome" value="{{ old('nome') }}" required>
                            @error('nome')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <!-- CNPJ/CPF -->
                        <div class="col">
                            <label for="cnpj_cpf" class="form-label">CNPJ/CPF</label>
                            <input type="text" class="form-control @error('cnpj_cpf') is-invalid @enderror"
                                id="cnpj_cpf" name="cnpj_cpf" value="{{ old('cnpj_cpf') }}" required>
                            @error('cnpj_cpf')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <!-- CEP -->
                    <div class="row mb-3">
                        <div class="col">
                            <label for="cep" class="form-label">CEP</label>
                            <input type="text" class="form-control @error('cep') is-invalid @enderror" id="cep"
                                name="cep" value="{{ old('cep') }}" required>
                            @error('cep')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <!-- Cidade -->
                        <div class="col">
                            <label for="cidade" class="form-label">Cidade</label>
                            <input type="text" class="form-control @error('cidade') is-invalid @enderror" id="cidade"
                                name="cidade" value="{{ old('cidade') }}" required>
                            @error('cidade')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <!-- Bairro -->
                        <div class="col">
                            <label for="bairro" class="form-label">Bairro</label>
                            <input type="text" class="form-control @error('bairro') is-invalid @enderror" id="bairro"
                                name="bairro" value="{{ old('bairro') }}" required>
                            @error('bairro')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col">
                            <label for="rua" class="form-label">Rua</label>
                            <input type="text" class="form-control @error('rua') is-invalid @enderror" id="rua"
                                name="rua" value="{{ old('rua') }}" required>
                            @error('rua')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <!-- Botão de Cadastro -->
                    <button type="submit" class="btn btn-primary">Cadastrar Empresa</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script>
    $(document).ready(function() {
        // Máscara para CNPJ/CPF
        $("#cnpj_cpf").keydown(function() {
            try {
                $("#cnpj_cpf").unmask();
            } catch (e) {}

            var tamanho = $("#cnpj_cpf").val().length;

            if (tamanho < 11) {
                $("#cnpj_cpf").mask("000.000.000-00");
            } else {
                $("#cnpj_cpf").mask("00.000.000/0000-00");
            }

            // Ajustando foco
            var elem = this;
            setTimeout(function() {
                elem.selectionStart = elem.selectionEnd = 10000;
            }, 0);
            var currentValue = $(this).val();
            $(this).val('');
            $(this).val(currentValue);
        });

        // Máscara para CEP e Telefone
        $('#cep').mask('00000-000');
        $('#telefone').mask('(00) 0000-0000');

        // Auto preencher os campos de endereço com o CEP
        $("#cep").change(function() {
            var cep = $(this).val().replace(/\D/g, ''); // Remove tudo que não é número
            if (cep.length === 8) {
                $.ajax({
                    url: 'https://viacep.com.br/ws/' + cep + '/json/',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
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
                    error: function() {
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