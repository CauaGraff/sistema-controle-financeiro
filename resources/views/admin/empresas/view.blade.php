@extends('admin._theme')

@section('title', 'Cadastro de Empresas')

@section('content')
<div class="container">
    <h1>{{ $empresa->nome }}</h1>
    <p>CNPJ/CPF: {{ $empresa->cnpj_cpf }}</p>
    <p>Endereço: {{ $empresa->rua }}, {{ $empresa->bairro }}, {{ $empresa->cidade }} - {{ $empresa->cep }}</p>

    <h2>Usuários com acesso</h2>
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
                        <!-- Aqui você pode adicionar ações como remover o usuário -->
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Launch demo modal
    </button>
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