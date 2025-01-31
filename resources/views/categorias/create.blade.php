@extends('_theme')

@section('title', 'Cadastrar Categoria de Conta')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Cadastrar Categoria de Conta</h1>

    <div class="card shadow-sm p-4">
        <form action="{{ route('categorias.store') }}" method="POST">
            @csrf
            @include('categorias.form') <!-- Inclusão do formulário -->

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-success" style="width: 100%;">Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection