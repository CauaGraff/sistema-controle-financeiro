@extends('admin._theme')

@section('title', 'Edição de Empresas')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6 col-md-8 col-10">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0">Editar </h5>
                <small class="text-white" style="font-size: 0.75rem;">
                    <i class="fas fa-clock"></i> Última atualização: {{ $empresa->updated_at->format('d/m/Y H:i') }}
                </small>
            </div>
            <div class="card shadow">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('adm.empresas.update', $empresa->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <div class="col">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome"
                                    name="nome" value="{{ old('nome', $empresa->nome) }}" required>
                                @error('nome')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="cnpj_cpf" class="form-label">CNPJ/CPF</label>
                                <input type="text" class="form-control @error('cnpj_cpf') is-invalid @enderror"
                                    id="cnpj_cpf" name="cnpj_cpf" value="{{ old('cnpj_cpf', $empresa->cnpj_cpf) }}"
                                    required>
                                @error('cnpj_cpf')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="cep" class="form-label">CEP</label>
                                <input type="text" class="form-control @error('cep') is-invalid @enderror" id="cep"
                                    name="cep" value="{{ old('cep', $empresa->cep) }}" required>
                                @error('cep')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="cidade" class="form-label">Cidade</label>
                                <input type="text" class="form-control @error('cidade') is-invalid @enderror"
                                    id="cidade" name="cidade" value="{{ old('cidade', $empresa->cidade) }}" required>
                                @error('cidade')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="bairro" class="form-label">Bairro</label>
                                <input type="text" class="form-control @error('bairro') is-invalid @enderror"
                                    id="bairro" name="bairro" value="{{ old('bairro', $empresa->bairro) }}" required>
                                @error('bairro')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="rua" class="form-label">Rua</label>
                                <input type="text" class="form-control @error('rua') is-invalid @enderror" id="rua"
                                    name="rua" value="{{ old('rua', $empresa->rua) }}" required>
                                @error('rua')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
                var cnpjCpf = $("#cnpj_cpf").val().replace(/\D/g, '');
                $("#cnpj_cpf").mask(cnpjCpf.length <= 11 ? "000.000.000-00" : "00.000.000/0000-00");
            }
            aplicarMascaraCnpjCpf();
            $("#cnpj_cpf").on("input", aplicarMascaraCnpjCpf);
            $('#cep').mask('00000-000');
            $("#cep").change(function () {
                var cep = $(this).val().replace(/\D/g, '');
                if (cep.length === 8) {
                    $("#modalLoading").modal('show');
                    $.ajax({
                        url: 'https://viacep.com.br/ws/' + cep + '/json/',
                        method: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            if (!data.erro) {
                                $('#rua').val(data.logradouro);
                                $('#bairro').val(data.bairro);
                                $('#cidade').val(data.localidade);
                            } else {
                                alert('CEP não encontrado!');
                            }
                            $("#modalLoading").modal('hide');
                        },
                        error: function () {
                            alert('Erro ao buscar o CEP!');
                            $("#modalLoading").modal('hide');
                        }
                    });
                } else {
                    alert('Por favor, digite um CEP válido.');
                }
            });
        });
    </script>
    @endsection