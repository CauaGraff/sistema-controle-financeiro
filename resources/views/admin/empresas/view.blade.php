@extends('admin._theme')

@section('title', 'Cadastro de Empresas')

@section("css")
<link rel="stylesheet" href="{{asset("css/dataTables.css")}}" />
@endsection
@section('content')

<div class="container">
    <h1>{{ $empresa->nome }}</h1>
    <p>CNPJ/CPF: {{ $empresa->cnpj_cpf }}</p>
    <p>Endereço: {{ $empresa->rua }}, {{ $empresa->bairro }}, {{ $empresa->cidade }} - {{ $empresa->cep }}</p>
    <div class="container row">
        <h2 class="text-center">Usuários com acesso</h2>
        <div class="text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                + Adcionar
            </button>
        </div>
    </div>
    <div class="table-responsive">

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->id }}</td>
                        <td>{{ $usuario->name }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>
                            <a href="{{route("adm.usuarios.edit", [$usuario->id])}}" class="btn"><i
                                    class="fa-solid fa-pen-to-square"></i></a>
                            <a href="{{route("adm.empresas.removeUsuario", ['idEmpresa' => $empresa->id, 'idUser' => $usuario->id])}}"
                                class="btn"><i class="fa-solid fa-trash"></i></a>
                            <a href="{{route("adm.usuarios.edit", [$usuario->id])}}" class="btn"><i
                                    class="fa-solid fa-eye"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">add user</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('adm.empresas.addUsuario', $empresa->id) }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="user_id">Selecionar Usuário</label>
                            <select name="user_id" id="user_id" class="form-control" data-live-search="true" required>
                                <option value="">Selecione um usuário</option>
                                @foreach ($allUsers as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Adicionar Usuário</button>
                </form>
            </div>
        </div>
    </div>
</div>

</div>
@endsection

@section(section: 'js')
<script src="{{asset("js/dataTables.js")}}"></script>
<script src="{{asset("js/toastr.min.js")}}"></script>

<script>
    $(".table").DataTable({
        language: {
            url: '{{asset("js/json/data_Table_pt_br.json")}}'
        }
    });
</script>
@endsection