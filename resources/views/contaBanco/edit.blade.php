@extends('_theme')

@section('content')
<div class="container">
    <h1>Editar Conta Bancária</h1>
    <form action="{{ route('contas_banco.update', $conta->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" value="{{ $conta->nome }}" required>
        </div>

        <div class="form-group">
            <label for="agencia">Agência</label>
            <input type="text" class="form-control" id="agencia" name="agencia" value="{{ $conta->agencia }}">
        </div>

        <div class="form-group">
            <label for="conta">Conta</label>
            <input type="text" class="form-control" id="conta" name="conta" value="{{ $conta->conta }}">
        </div>
        <button type="submit" class="btn btn-primary">Atualizar</button>
    </form>
</div>
@endsection