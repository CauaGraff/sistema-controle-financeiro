@extends('admin._theme')

@section("title", "Usuarios")

@section("css")
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.css" />
@endsection
@section('content')
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
                            <a href="" class="btn"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="" class="btn"><i class="fa-solid fa-trash"></i></a>
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
<script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
<script>
    $(".table").DataTable();
</script>
@endsection