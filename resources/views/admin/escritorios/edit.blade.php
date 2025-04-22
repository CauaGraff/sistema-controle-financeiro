@extends('admin._theme')
@section('title', 'Editar Escritório')
@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-warning text-white">Editar Escritório</div>
                <div class="card-body">
                    @include('admin.escritorios.form', ['escritorio' => $escritorio])
                </div>
            </div>
        </div>
    </div>
@endsection