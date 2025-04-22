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
            <label class="form-label" for="name">Nome</label>
            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $escritorio->name ?? '') }}" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col">
            <label class="form-label" for="cnpj">CNPJ</label>
            <input type="text" id="cnpj" name="cnpj" class="form-control @error('cnpj') is-invalid @enderror"
                value="{{ old('cnpj', $escritorio->cnpj ?? '') }}">
            @error('cnpj') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-4">
            <label class="form-label" for="cep">CEP</label>
            <input type="text" id="cep" name="cep" class="form-control @error('cep') is-invalid @enderror"
                value="{{ old('cep', $escritorio->cep ?? '') }}">
            @error('cep') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-4">
            <label class="form-label" for="uf">UF</label>
            <input type="text" id="uf" name="uf" class="form-control @error('uf') is-invalid @enderror"
                value="{{ old('uf', $escritorio->uf ?? '') }}">
            @error('uf') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-4">
            <label class="form-label" for="complemento">Complemento</label>
            <input type="text" id="complemento" name="complemento"
                class="form-control @error('complemento') is-invalid @enderror"
                value="{{ old('complemento', $escritorio->complemento ?? '') }}">
            @error('complemento') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <label class="form-label" for="rua">Rua</label>
            <input type="text" id="rua" name="rua" class="form-control @error('rua') is-invalid @enderror"
                value="{{ old('rua', $escritorio->rua ?? '') }}">
            @error('rua') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col">
            <label class="form-label" for="bairro">Bairro</label>
            <input type="text" id="bairro" name="bairro" class="form-control @error('bairro') is-invalid @enderror"
                value="{{ old('bairro', $escritorio->bairro ?? '') }}">
            @error('bairro') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col">
            <label class="form-label" for="cidade">Cidade</label>
            <input type="text" id="cidade" name="cidade" class="form-control @error('cidade') is-invalid @enderror"
                value="{{ old('cidade', $escritorio->cidade ?? '') }}">
            @error('cidade') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label" for="obs">Observações</label>
        <textarea id="obs" name="obs" rows="3"
            class="form-control @error('obs') is-invalid @enderror">{{ old('obs', $escritorio->obs ?? '') }}</textarea>
        @error('obs') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-check mb-3">
        <input type="checkbox" id="active" name="active" value="1" class="form-check-input" {{ old('active', $escritorio->active ?? true) ? 'checked' : '' }}>
        <label class="form-check-label" for="active">Ativo</label>
    </div>

    <button class="btn btn-primary w-100">
        {{ $isEdit ? 'Atualizar Escritório' : 'Cadastrar Escritório' }}
    </button>
</form>
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

@section('js')
    <script src="{{ asset('js/jquery.mask.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#cep').mask('00000-000');

            // Desabilita os campos de endereço até o CEP ser validado
            $('#rua, #bairro, #cidade, #uf').prop('disabled', true);

            // Preenchimento via ViaCEP
            $("#cep").on('change', function () {
                var cep = $(this).val().replace(/\D/g, '');
                if (cep.length !== 8) {
                    alert('Por favor, digite um CEP válido.');
                    return;
                }

                // Exibe modal de carregamento
                $("#modalLoading").modal('show');

                $.getJSON('https://viacep.com.br/ws/' + cep + '/json/', function (data) {
                    if (data.erro) {
                        alert('CEP não encontrado!');
                        $("#modalLoading").modal('hide'); // Fecha o modal em caso de erro
                        return;
                    }

                    // Preenche campos
                    $('#rua').val(data.logradouro);
                    $('#bairro').val(data.bairro);
                    $('#cidade').val(data.localidade);
                    $('#uf').val(data.uf);

                    // Habilita para edição caso necessário
                    $('#rua, #bairro, #cidade, #uf').prop('disabled', false);

                    // Fecha o modal após o sucesso
                    $("#modalLoading").modal('hide');
                })
                    .fail(function () {
                        $("#modalLoading").modal('hide'); // Fecha o modal em caso de falha
                        alert('Erro ao buscar o CEP!');
                    });
            });
        });
    </script>
@endsection