@extends('admin._theme')

@section("title", "Usuarios Cadastro")

@section(section: 'content')
<div class="row justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="col-lg-4 col-md-6 col-10">
        <div class="card shadow">
            <div class="card-body p-4">
                <form method="POST" action="{{route("adm.usuarios.update", [$user->id])}}">
                    @csrf
                    @method('PUT')
                    <!-- Nome -->
                    <div class="row mb-3">
                        <div class="col">
                            <label for="name" class="form-label">Nome</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{$user->name}}" required>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{$user->email}}" required>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label for="password" class="form-label">Senha</label>
                            <select name="active" id="active" class="form-select" aria-label="Default select example">
                                <option value="0" {{$user->active == 0 ? "selected" : ""}}>Desativado</option>
                                <option value="1" {{$user->active == 1 ? "selected" : ""}}>Ativo</option>
                            </select>
                        </div>
                    </div>

                    <!-- Senha -->
                    <div class="row mb-3">
                        <div class="col">
                            <label for="password" class="form-label">Senha</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Botão de Cadastro -->
                    <button type="submit" class="btn btn-success">Salvar</button>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection