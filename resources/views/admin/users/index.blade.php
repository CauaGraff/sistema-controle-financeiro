@extends('admin._theme')

@section("title", "Usuarios")

@section("css")
<link rel="stylesheet" href="{{asset("css/dataTables.css")}}" />
<link rel="stylesheet" href="{{asset("css/toastr.min.css")}}" />
@endsection
@section('content')


<div class="container mt-5">
    <h2 class="text-center">Lista de Usuários {{$type}}</h2>
    <div class="text-end">
        <a href="{{route("adm.cadastro.usuarios", [$type])}}" class="btn btn-primary ">+ Adcionar</a>
    </div>
    <!-- Verifica se existem usuários -->
    @if ($users->isEmpty())
        <p class="text-center">Nenhum usuário cadastrado.</p>
    @else
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="table-dark">
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
                            <td>{{ $user->active == 1 ? "Ativo" : "Desativado" }}</td>
                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{route("adm.usuarios.edit", [$user->id])}}" class="btn"><i
                                        class="fa-solid fa-pen-to-square"></i></a>
                                <a href="{{route("adm.usuarios.delete", [$user->id])}}" class="btn"><i
                                        class="fa-solid fa-trash"></i></a>
                                <a href="{{route("adm.usuarios.edit", [$user->id])}}" class="btn"><i
                                        class="fa-solid fa-eye"></i></a>
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
<script src="{{asset("js/toastr.min.js")}}"></script>

<script>
    $(".table").DataTable({
        responsive: true
    });
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "3000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    @if(Session::has('alert-success'))
        toastr.success("{{ Session::get('alert-success') }} ")


    @elseif (Session::has('alert-warning'))
        toastr.warning("{{ Session::get('alert-success') }} ")


    @elseif (Session::has('alert-danger'))
        toastr.error("{{ Session::get('alert-success') }} ")


    @endif
</script>
@endsection