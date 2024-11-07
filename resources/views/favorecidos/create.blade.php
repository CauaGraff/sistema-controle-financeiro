@extends('_theme')

@section('title', 'Cadastrar Favorecido')

@section('content')
<div class="container mt-4">
    <h1>Cadastrar Novo Favorecido</h1>

    <form action="{{ route('favorecidos.store') }}" method="POST">
        @csrf
        @include('favorecidos.form')
        <button type="submit" class="btn btn-success">Cadastrar</button>
    </form>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function () {
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
    });
</script>
@endsection