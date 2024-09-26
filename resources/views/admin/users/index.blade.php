@extends('admin._theme')

@section("title", "Usuarios")

@section("css")
<link rel="stylesheet" href="{{asset("css/dataTables.css")}}" />
@endsection
@section('content')

<div class="toast text-bg-primary" role="alert">
    <div class="toast-body">
        <div class="d-flex gap-4">
            <span><i class="fa-solid fa-circle-check fa-lg"></i></span>
            <div class="d-flex flex-grow-1 align-items-center">
                <span class="fw-semibold">Welcome to the room</span>
                <button type="button" class="btn-close btn-close-white btn-close-sm ms-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>
</div>
<div class="container mt-5">
    <h2 class="text-center">Lista de Usuários {{$type}}</h2>
    <a href="{{route("adm.cadastro.usuarios", [$type])}}" class="btn btn-primary">+ Adcionar</a>
    <!-- Verifica se existem usuários -->
    @if ($users->isEmpty())
        <p class="text-center">Nenhum usuário cadastrado.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Data de Cadastro</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->active }}</td>
                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{route("adm.usuarios.edit", [$user->id])}}" class="btn"><i
                                    class="fa-solid fa-pen-to-square"></i></a>
                            <a href="{{route("adm.usuarios.delete", [$user->id])}}" class="btn"><i
                                    class="fa-solid fa-trash"></i></a>
                            <a href="" class="btn"><i class="fa-solid fa-eye"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection


@section(section: 'js')
<script src="{{asset("js/dataTables.js")}}"></script>
<script>
    $(".table").DataTable();
</script>
@endsection