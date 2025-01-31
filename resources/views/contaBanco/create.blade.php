@extends('_theme')

@section('title', 'Cadastrar Conta Bancária')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Cadastrar Conta Bancária</h1>

    <div class="card shadow-sm p-4">
        <form action="{{ route('contas_banco.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome"
                    value="{{ old('nome') }}">
                @error('nome')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="agencia" class="form-label">Agência</label>
                <input type="text" class="form-control @error('agencia') is-invalid @enderror" id="agencia"
                    name="agencia" value="{{ old('agencia') }}">
                @error('agencia')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="conta" class="form-label">Nº Conta</label>
                <input type="text" class="form-control @error('conta') is-invalid @enderror" id="conta" name="conta"
                    value="{{ old('conta') }}">
                @error('conta')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-success" style="width: 100%;">Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection