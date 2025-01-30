@extends('admin._theme')

@section("title", "Cadastro de Usuários")

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-lg-6 col-md-8 col-sm-10">
        <div class="card shadow">
            <div class="card-header text-center bg-primary text-white">
                <h4>Cadastro de Usuário</h4>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{route('adm.cadastro.usuarios.post')}}">
                    @csrf
                    <input type="hidden" class="form-control" id="typeuser" name="typeuser" value="{{ $typeuser }}">

                    <!-- Nome -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Senha -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Senha</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Botão de Cadastro -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection