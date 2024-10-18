@extends('admin._theme')

@section('title', 'Editar Categoria de Conta')

@section('content')
<div class="container mt-4">
    <h1>Editar Categoria de Conta</h1>

    <form action="{{ route('categorias.update', $categoria->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('categorias.form') <!-- Inclui o formulário -->
        <button type="submit" class="btn btn-primary">Atualizar</button>
    </form>
</div>
@endsection