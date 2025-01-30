@extends('admin._theme')

@section('title', 'Cadastro de Empresas')

@section("css")
<link rel="stylesheet" href="{{ asset("css/dataTables.css") }}" />
@endsection

@section('content')
<div class="container my-4">
    <div class="card shadow-lg p-4">
        <div class="text-center mb-4">
            <h2 class="fw-bold">{{ $empresa->nome }}</h2>
            <p class="text-muted">Gerencie os dados e usuários vinculados a esta empresa.</p>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <p><strong>CNPJ/CPF:</strong> {{ $empresa->cnpj_cpf }}</p>
                <p><strong>Endereço:</strong> {{ $empresa->rua }}, {{ $empresa->bairro }}, {{ $empresa->cidade }} -
                    {{ $empresa->cep }}
                </p>
            </div>
            <div class="col-md-6 text-md-end text-center">
                <a class="btn btn-success px-4 py-2" href="{{ route('home') }}">
                    <i class="fas fa-chart-line"></i> Acessar Controle Financeiro
                </a>
            </div>
        </div>
        <!-- Abas de Navegação -->
        <ul class="nav nav-tabs mb-3" id="empresaTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="usuarios-tab" data-bs-toggle="tab" data-bs-target="#usuarios-pane"
                    type="button" role="tab" aria-controls="usuarios-pane" aria-selected="true">
                    <i class="fas fa-users"></i> Usuários
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="exportacao-tab" data-bs-toggle="tab" data-bs-target="#exportacao-pane"
                    type="button" role="tab" aria-controls="exportacao-pane" aria-selected="false">
                    <i class="fas fa-file-export"></i> Exportação
                </button>
            </li>
        </ul>
        <!-- Conteúdo das Abas -->
        <div class="tab-content" id="empresaTabContent">
            <!-- Tab: Usuários -->
            <div class="tab-pane fade show active" id="usuarios-pane" role="tabpanel" aria-labelledby="usuarios-tab">
                <div class="card shadow-sm p-3">
                    @include('admin.empresas.tabelausuarios')
                </div>
            </div>
            <!-- Tab: Exportação -->
            <div class="tab-pane fade" id="exportacao-pane" role="tabpanel" aria-labelledby="exportacao-tab">
                <div class="card shadow-sm p-3">
                    @include('admin.empresas.formexportacao')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection