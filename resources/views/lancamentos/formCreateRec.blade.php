@extends('_theme')

@section('content')
<div class="container mt-5">
    <h1>Cadastrar Recebimentos</h1>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('lancamentos.recebimentos.store') }}" method="POST">
        @csrf
        @include('lancamentos.form')
        <button type="submit" class="btn btn-primary">Cadastrar Lançamento</button>
    </form>
</div>
@endsection