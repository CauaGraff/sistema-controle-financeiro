@extends('_theme')

@section('title', 'Editar Favorecido')

@section('content')
<div class="container mt-4">
    <h1>Editar Favorecido</h1>

    <form action="{{ route('favorecidos.update', $favorecido->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('favorecidos.form')
        <button type="submit" class="btn btn-success">Atualizar</button>
    </form>
</div>
@endsection