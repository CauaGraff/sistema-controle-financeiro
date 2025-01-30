@section("css")
<style>
    .select-box {
        position: relative;
        display: flex;
        width: 400px;
        flex-direction: column;
    }

    .select-box .options-container {
        background: #2f3640;
        color: #f5f6fa;
        max-height: 0;
        width: 100%;
        opacity: 0;
        transition: all 0.4s;
        border-radius: 8px;
        overflow: hidden;

        order: 1;
    }

    .selected {
        background: #2f3640;
        border-radius: 8px;
        margin-bottom: 8px;
        color: #f5f6fa;
        position: relative;

        order: 0;
    }

    .selected::after {
        content: "";
        background: url("img/arrow-down.svg");
        background-size: contain;
        background-repeat: no-repeat;

        position: absolute;
        height: 100%;
        width: 32px;
        right: 10px;
        top: 5px;

        transition: all 0.4s;
    }

    .select-box .options-container.active {
        max-height: 240px;
        opacity: 1;
        overflow-y: scroll;
        margin-top: 54px;
    }

    .select-box .options-container.active+.selected::after {
        transform: rotateX(180deg);
        top: -6px;
    }

    .select-box .options-container::-webkit-scrollbar {
        width: 8px;
        background: #0d141f;
        border-radius: 0 8px 8px 0;
    }

    .select-box .options-container::-webkit-scrollbar-thumb {
        background: #525861;
        border-radius: 0 8px 8px 0;
    }

    .select-box .option,
    .selected {
        padding: 12px 24px;
        cursor: pointer;
    }

    .select-box .option:hover {
        background: #414b57;
    }

    .select-box label {
        cursor: pointer;
    }

    .select-box .option .radio {
        display: none;
    }

    /* Searchbox */

    .search-box input {
        width: 100%;
        padding: 12px 16px;
        font-family: "Roboto", sans-serif;
        font-size: 16px;
        position: absolute;
        border-radius: 8px 8px 0 0;
        z-index: 100;
        border: 8px solid #2f3640;

        opacity: 0;
        pointer-events: none;
        transition: all 0.4s;
    }

    .search-box input:focus {
        outline: none;
    }

    .select-box .options-container.active~.search-box input {
        opacity: 1;
        pointer-events: auto;
    }
</style>
@endsection

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
                        <div class="select-box">
                            <div class="options-container">
                                <div class="option">
                                    <input type="radio" class="radio" id="automobiles" name="category" />
                                    <label for="automobiles">Automobiles</label>
                                </div>

                                <div class="option">
                                    <input type="radio" class="radio" id="film" name="category" />
                                    <label for="film">Film & Animation</label>
                                </div>

                                <div class="option">
                                    <input type="radio" class="radio" id="science" name="category" />
                                    <label for="science">Science & Technology</label>
                                </div>

                                <div class="option">
                                    <input type="radio" class="radio" id="art" name="category" />
                                    <label for="art">Art</label>
                                </div>

                                <div class="option">
                                    <input type="radio" class="radio" id="music" name="category" />
                                    <label for="music">Music</label>
                                </div>

                                <div class="option">
                                    <input type="radio" class="radio" id="travel" name="category" />
                                    <label for="travel">Travel & Events</label>
                                </div>

                                <div class="option">
                                    <input type="radio" class="radio" id="sports" name="category" />
                                    <label for="sports">Sports</label>
                                </div>

                                <div class="option">
                                    <input type="radio" class="radio" id="news" name="category" />
                                    <label for="news">News & Politics</label>
                                </div>

                                <div class="option">
                                    <input type="radio" class="radio" id="tutorials" name="category" />
                                    <label for="tutorials">Tutorials</label>
                                </div>
                            </div>

                            <div class="selected">
                                Select Video Category
                            </div>

                            <div class="search-box">
                                <input type="text" placeholder="Start Typing..." />
                            </div>
                        </div>
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
    });
</script>
@endsection