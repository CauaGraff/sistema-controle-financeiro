@extends('admin._theme')

@section("title", "Página Inicial")

@section('content')
<div class="row mb-3">
    <!-- Card de Clientes Ativos -->
    <div class="col">
        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Clientes Ativos</h5>
                <p class="card-text">{{ $clientesAtivos }}</p> <!-- Valor dinâmico para Clientes Ativos -->
            </div>
        </div>
    </div>

    <!-- Card de Empresas Ativas -->
    <div class="col">
        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Empresas Ativas</h5>
                <p class="card-text">{{ $empresasAtivas }}</p> <!-- Valor dinâmico para Empresas Ativas -->
            </div>
        </div>
    </div>

    <!-- Card de Usuários do Escritório -->
    <div class="col">
        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Usuários do Escritório</h5>
                <p class="card-text">{{ $usuariosEscritorio }}</p> <!-- Valor dinâmico para Usuários do Escritório -->
            </div>
        </div>
    </div>
</div>
@endsection