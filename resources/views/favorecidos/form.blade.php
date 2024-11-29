<div class="row mb-3">
    <div class="col-md-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome') }}">
    </div>
    <div class="col-md-3">
        <label for="cnpj_cpf" class="form-label">CNPJ/CPF</label>
        <input type="text" class="form-control" id="cnpj_cpf" name="cnpj_cpf" value="{{ old('cnpj_cpf') }}">
    </div>
    <div class="col-md-2">
        <label for="telefone" class="form-label">Telefone</label>
        <input type="text" class="form-control" id="telefone" name="telefone" value="{{ old('telefone') }}">
    </div>
</div>

<div class="row mb-3">

    <div class="col-md-2">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
    </div>
    <div class="col-md-2">
        <label for="cep" class="form-label">CEP</label>
        <input type="text" class="form-control" id="cep" name="cep" value="{{ old('cep') }}">
    </div>
    <div class="col-md-2">
        <label for="uf" class="form-label">UF</label>
        <input type="text" class="form-control" id="uf" name="uf" value="{{ old('uf') }}" maxlength="2">
    </div>
    <div class="col-md-2">
        <label for="cidade" class="form-label">Cidade</label>
        <input type="text" class="form-control" id="cidade" name="cidade" value="{{ old('cidade') }}">
    </div>
</div>

<div class="row mb-3">

    <div class="col-md-2">
        <label for="bairro" class="form-label">Bairro</label>
        <input type="text" class="form-control" id="bairro" name="bairro" value="{{ old('bairro') }}">
    </div>
    <div class="col-md-2">
        <label for="rua" class="form-label">Rua</label>
        <input type="text" class="form-control" id="rua" name="rua" value="{{ old('rua') }}">
    </div>
    <div class="col-md-4">
        <label for="complemento" class="form-label">Complemento</label>
        <input type="text" class="form-control" id="complemento" name="complemento" value="{{ old('complemento') }}">
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-8">
        <label for="tipo" class="form-label">Tipo</label>
        <select class="form-select" id="tipo" name="tipo">
            <option value="F" {{ (old('complemento') == 'F') ? 'selected' : '' }}>Fornecedor</option>
            <option value="C" {{ (old('complemento') == 'C') ? 'selected' : '' }}>Cliente</option>
        </select>
    </div>
</div>

@section('js')
<script src="{{asset("js/jquery.mask.min.js")}}"></script>
<script>
    $(document).ready(function () {
        $("#cpf_cnpj").keydown(function () {
            try {
                $("#cpf_cnpj").unmask();
            } catch (e) { }

            var tamanho = $("#cpf_cnpj").val().length;

            if (tamanho < 11) {
                $("#cpf_cnpj").mask("000.000.000-00");
            } else {
                $("#cpf_cnpj").mask("00.000.000/0000-00");
            }

            // ajustando foco
            var elem = this;
            setTimeout(function () {
                // mudo a posição do seletor
                elem.selectionStart = elem.selectionEnd = 10000;
            }, 0);
            // reaplico o valor para mudar o foco
            var currentValue = $(this).val();
            $(this).val('');
            $(this).val(currentValue);
        });
        $("#cep").change(function () {
            var cep = $(this).val().replace(/\D/g, ''); // Remove tudo que não é número

            // Verifica se o CEP tem 8 dígitos
            if (cep.length === 8) {
                $.ajax({
                    url: 'https://viacep.com.br/ws/' + cep + '/json/',
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        if (data.erro) {
                            alert('CEP não encontrado!');
                        } else {
                            // Preenche os campos automaticamente com os dados retornados
                            $('#rua').val(data.logradouro);
                            $('#bairro').val(data.bairro);
                            $('#cidade').val(data.localidade);
                            $('#uf').val(data.uf);

                            // Desabilita os campos de endereço após preenchê-los
                            $('#rua').prop('disabled', true);
                            $('#bairro').prop('disabled', true);
                            $('#cidade').prop('disabled', true);
                            $('#uf').prop('disabled', true);
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
        $('#cep').mask('00000-000');

    })
</script>
@endsection