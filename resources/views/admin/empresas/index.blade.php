@extends('admin._theme')

@section("title", "Usuarios")

@section("css")
<link rel="stylesheet" href="{{asset("css/dataTables.css")}}" />
@endsection

@section('content')
<div class="container mt-5">
    <h2 class="text-center">Empresas</h2>
    <div class="d-flex justify-content-end align-items-center mb-3">
        <a href="{{route("adm.cadastro.empresas")}}" class="btn btn-primary ">+ Adcionar</a>
    </div>
    <!-- Verifica se existem usuários -->
    @if ($empresas->isEmpty())
    <p class="text-center">Nenhuma empresa cadastrado.</p>
    @else
    <div class="table-responsive">
        <table class="table table-striped stripe row-border order-column" style="width:100%">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>CNPJ</th>
                    <th>Status</th>
                    <th>Data de Cadastro</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($empresas as $empresa)
                <tr>
                    <td>{{ $empresa->id }}</td>
                    <td>{{ $empresa->nome }}</td>
                    <td>{{ $empresa->cnpj_cpf }}</td>
                    <td>{{ $empresa->active == 1 ? "Ativo" : "Desativado" }}</td>
                    <td>{{ $empresa->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{route("adm.empresas.edit", [$empresa->id])}}" class="btn"><i
                                class="fa-solid fa-pen-to-square"></i></a>
                        <a href="{{route("adm.empresas.delete", [$empresa->id])}}" class="btn"><i
                                class="fa-solid fa-trash"></i></a>
                        <a href="{{route("adm.empresas.show", [$empresa->id])}}" class="btn"><i
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
<script>
    $(".table").DataTable({});
</script>
@endsection