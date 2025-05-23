@extends('admin._theme')

@section("title", "Usuarios")

@section("css")
<link rel="stylesheet" href="{{asset("css/dataTables.css")}}" />
<link rel="stylesheet" href="{{asset("css/toastr.min.css")}}" />
@endsection
@section('content')


<div class="container mt-5">
    <h2 class="text-center">Lista de Usuários {{$type}}</h2>
    <div class="d-flex justify-content-end align-items-center mb-3">
        <a href="{{route("adm.cadastro.usuarios", [$type])}}" class="btn btn-primary ">+ Adcionar</a>
    </div>
    <!-- Verifica se existem usuários -->
    @if ($users->isEmpty())
        <p class="text-center">Nenhum usuário cadastrado.</p>
    @else
        <div class="">
            <table class="table table-striped stripe row-border order-column" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Data de Cadastro</th>
                        <th class="text-center">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->active == 1 ? "Ativo" : "Desativado" }}</td>
                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                <a href="{{route("adm.usuarios.edit", [$user->id])}}" class="btn btn-sm btn-warning"><i
                                        class="fa-solid fa-pen-to-square" style="color: white;"></i></a>
                                <a href="{{route("adm.usuarios.delete", [$user->id])}}" class="btn btn-sm btn-danger"><i
                                        class="fa-solid fa-trash" style="color: white;"></i></a>
                                <a href="{{route("adm.usuarios.edit", [$user->id])}}" class="btn btn-sm btn-info"><i
                                        class="fa-solid fa-eye" style="color: white;"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection


@section(section: 'js')
<script src="{{asset("js/dataTables.js")}}"></script>
<script src="{{asset("js/dataTables.fixedColumns.js")}}"></script>
<script src="{{asset("js/fixedColumns.dataTables.js")}}"></script>
<script>
    $(".table").DataTable({
        language: {
            url: '{{asset("js/json/data_Table_pt_br.json")}}'
        },
        fixedColumns: {
            start: 0,
            end: 1
        },
        scrollX: true
    });
</script>
@endsection