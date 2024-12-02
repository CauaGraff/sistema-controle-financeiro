@extends('_theme')

@section('content')
<div class="container">
    <h1>Cadastrar Conta Bancária</h1>
    <form action="{{ route('contas_banco.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" value="{{old("nome")}}">
            @error('nome')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="agencia">Agência</label>
            <input type="text" class="form-control" id="agencia" name="agencia" value="{{old("agencia")}}">
            @error('agencia')
                <div class=" invalid-feedback">{{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group">
            <label for="conta">Nº Conta</label>
            <input type="text" class="form-control" id="conta" name="conta" value="{{old("conta")}}">
            @error('agencia')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Cadastrar</button>
    </form>
</div>
@endsection