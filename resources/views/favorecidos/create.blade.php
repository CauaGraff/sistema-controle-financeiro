@extends('admin._theme')

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