@extends('_theme')

@section('title', 'Cadastrar Categoria de Conta')

@section('content')
<div class="container mt-4">
    <h1>Cadastrar Categoria de Conta</h1>

    <form action="{{ route('categorias.store') }}" method="POST">
        @csrf
        @include('categorias.form') <!-- Inclui o formulário -->
        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
</div>
@endsection