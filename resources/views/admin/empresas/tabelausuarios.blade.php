<h2 class="mb-3">Usuários com Acesso</h2>
<div class="d-flex justify-content-between align-items-center mb-3">
    <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
        + Adicionar Usuário
    </button>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover table-bordered stripe row-border order-column text-nowrap">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->id }}</td>
                    <td>{{ $usuario->name }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td class="text-center">
                        <!-- Botões de ação com espaçamento e títulos -->
                        <a href="{{ route('adm.usuarios.edit', [$usuario->id]) }}" class="btn btn-warning btn-sm mx-1"
                            title="Editar">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <a href="{{ route('adm.empresas.removeUsuario', ['idEmpresa' => $empresa->id, 'idUser' => $usuario->id]) }}"
                            class="btn btn-danger btn-sm mx-1" title="Remover">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                        <a href="{{ route('adm.usuarios.edit', [$usuario->id]) }}" class="btn btn-info btn-sm mx-1"
                            title="Visualizar">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Adicionar Usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('adm.empresas.addUsuario', $empresa->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Campo Selecionar Usuário -->
                    <div class="form-group mb-3">
                        <label for="user_id" class="form-label">Selecionar Usuário</label>
                        <select name="user_id" id="user_id" class="form-select" required>
                            <option value="">Selecione um usuário</option>
                            @foreach ($allUsers as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Adicionar</button>
                </div>
            </form>
        </div>
    </div>
</div>