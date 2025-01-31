<h2 class="mb-3">Usuários com Acesso</h2>
<div class="d-flex justify-content-between align-items-center mb-3">
    <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
        + Adicionar Usuário
    </button>
</div>

<div class="">
    <table class="table table-striped stripe row-border order-column" style="width:100%">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th class="text-center">Ação</th>
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
                            <i class="fa-solid fa-pen-to-square" style="color: white;"></i>
                        </a>
                        <a href="{{ route('adm.empresas.removeUsuario', ['idEmpresa' => $empresa->id, 'idUser' => $usuario->id]) }}"
                            class="btn btn-danger btn-sm mx-1" title="Remover">
                            <i class="fa-solid fa-trash" style="color: white;"></i>
                        </a>
                        <a href="{{ route('adm.usuarios.edit', [$usuario->id]) }}" class="btn btn-info btn-sm mx-1"
                            title="Visualizar">
                            <i class="fa-solid fa-eye" style="color: white;"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="exampleModalLabel">Adicionar Usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('adm.empresas.addUsuario', $empresa->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="buscarUsuario" class="form-label">Buscar Usuário</label>
                        <input type="text" id="buscarUsuario" class="form-control" placeholder="Digite para buscar..."
                            autocomplete="off">
                    </div>

                    <div id="resultadosBusca" class="list-group mb-3"></div>

                    <h6>Usuários Selecionados:</h6>
                    <div id="usuariosSelecionados" class="border rounded p-3 bg-light">
                        <p class="text-muted" id="nenhumUsuario">Nenhum usuário selecionado.</p>
                    </div>

                    <input type="hidden" name="usuarios" id="usuariosInput">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Adicionar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@section('js')
<script src="{{ asset("js/dataTables.js") }}"></script>
<script>
    $(document).ready(function () {
        $(".table").DataTable({
            language: {
                url: '{{ asset("js/json/data_Table_pt_br.json") }}'
            },
            responsive: true,
            autoWidth: false
        });
        var usuariosSelecionados = [];

        $('#buscarUsuario').on('keyup', function () {
            var termo = $(this).val();

            if (termo.length > 2) {
                $.ajax({
                    url: '{{ route("buscar.usuario") }}',
                    method: 'GET',
                    data: { termo: termo },
                    success: function (data) {
                        var resultados = $('#resultadosBusca');
                        resultados.empty();

                        if (data.length > 0) {
                            data.forEach(function (usuario) {
                                if (!usuariosSelecionados.includes(usuario.id)) {
                                    resultados.append('<button type="button" class="list-group-item list-group-item-action opcao-usuario" data-id="' + usuario.id + '" data-nome="' + usuario.name + '">' +
                                        '<i class="fa-solid fa-user me-2"></i>' + usuario.name +
                                        '</button>');
                                }
                            });
                        } else {
                            resultados.append('<p class="text-muted text-center">Nenhum usuário encontrado</p>');
                        }
                    }
                });
            } else {
                $('#resultadosBusca').empty();
            }
        });

        // Adicionar usuário selecionado à lista
        $(document).on('click', '.opcao-usuario', function () {
            var usuarioId = $(this).data('id');
            var usuarioNome = $(this).data('nome');

            if (!usuariosSelecionados.includes(usuarioId)) {
                usuariosSelecionados.push(usuarioId);

                $('#usuariosSelecionados').append('<div class="d-flex justify-content-between align-items-center p-2 mb-1 bg-white border rounded usuario-item" data-id="' + usuarioId + '">' +
                    '<span><i class="fa-solid fa-user me-2"></i>' + usuarioNome + '</span>' +
                    '<button type="button" class="btn btn-outline-danger btn-sm remove-usuario" data-id="' + usuarioId + '">' +
                    '<i class="fa-solid fa-trash"></i>' +
                    '</button>' +
                    '</div>');

                atualizarCampoUsuarios();
                $('#nenhumUsuario').hide();
            }

            $('#resultadosBusca').empty();
            $('#buscarUsuario').val('');
        });

        // Remover usuário da lista
        $(document).on('click', '.remove-usuario', function () {
            var usuarioId = $(this).data('id');
            usuariosSelecionados = usuariosSelecionados.filter(id => id != usuarioId);
            $(this).parent().remove();
            atualizarCampoUsuarios();

            if (usuariosSelecionados.length === 0) {
                $('#nenhumUsuario').show();
            }
        });

        // Atualizar campo oculto com IDs selecionados
        function atualizarCampoUsuarios() {
            $('#usuariosInput').val(usuariosSelecionados.join(','));
        }
    });
</script>
@endsection