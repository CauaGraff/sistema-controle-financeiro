@extends('admin._theme')

@section("title", "Usuarios Cadastro")

@section(section: 'content')
<div class="row justify-content-center">
    <div class="col-lg-6 col-md-8 col-10"> <!-- Expandida a largura -->
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0">Editar Usuário</h5>
                <small class="text-white" style="font-size: 0.75rem;">
                    <i class="fas fa-clock"></i> Última atualização: {{ $user->updated_at->format('d/m/Y H:i') }}
                </small>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route("adm.usuarios.update", [$user->id]) }}">
                    @csrf
                    @method('PUT')

                    <!-- Nome e Email -->
                    <div class="row mb-3">
                        <div class="col">
                            <label for="name" class="form-label">Nome</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ $user->name }}" required>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ $user->email }}" required>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="row mb-3">
                        <div class="col">
                            <label for="active" class="form-label">Status</label>
                            <select name="active" id="active" class="form-select">
                                <option value="0" {{ $user->active == 0 ? "selected" : "" }}>Desativado</option>
                                <option value="1" {{ $user->active == 1 ? "selected" : "" }}>Ativo</option>
                            </select>
                        </div>
                    </div>

                    <!-- Senha -->
                    <div class="row mb-3">
                        <div class="col">
                            <label for="password" class="form-label">Senha</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password">
                            <small class="text-muted d-flex align-items-center">
                                <i class="fas fa-info-circle me-1"></i> Deixe em branco para manter a senha atual.
                            </small>
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Botão de Salvar -->
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-save"></i> Salvar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection