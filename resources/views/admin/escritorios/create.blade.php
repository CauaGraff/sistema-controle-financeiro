@extends('admin._theme')
@section('title', 'Novo Escritório')
@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">Cadastro de Escritório</div>
                <div class="card-body">
                    @include('admin.escritorios.form')
                </div>
            </div>
        </div>
    </div>
@endsection