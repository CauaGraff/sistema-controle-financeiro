@extends('admin._theme')

@section('title', 'Cadastro de Empresas')

@section("css")
<link rel="stylesheet" href="{{ asset("css/dataTables.css") }}" />
@endsection

@section('content')
<div class="container my-4">
    <h1 class="text-center">{{ $empresa->nome }}</h1>
    <p><strong>CNPJ/CPF:</strong> {{ $empresa->cnpj_cpf }}</p>
    <p><strong>Endereço:</strong> {{ $empresa->rua }}, {{ $empresa->bairro }}, {{ $empresa->cidade }} -
        {{ $empresa->cep }}
    </p>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a class="btn btn-success" href="{{ route('home') }}">Acessar Controle Financeiro</a>
    </div>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="usuarios-tab" data-bs-toggle="tab" data-bs-target="#usuarios-tab-pane"
                type="button" role="tab" aria-controls="usuarios-tab-pane" aria-selected="true">Usuários</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="detalhes-tab" data-bs-toggle="tab" data-bs-target="#detalhes-tab-pane"
                type="button" role="tab" aria-controls="detalhes-tab-pane" aria-selected="false">Exportação</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="config-tab" data-bs-toggle="tab" data-bs-target="#config-tab-pane"
                type="button" role="tab" aria-controls="config-tab-pane" aria-selected="false">Configurações</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <!-- Tab: Usuários -->
        <div class="tab-pane fade show active" id="usuarios-tab-pane" role="tabpanel" aria-labelledby="usuarios-tab"
            tabindex="0">
            @include('admin.empresas.tabelausuarios')
        </div>

        <!-- Tab: Detalhes -->
        <div class="tab-pane fade" id="detalhes-tab-pane" role="tabpanel" aria-labelledby="detalhes-tab" tabindex="0">
            <!-- form exportação -->
            @include('admin.empresas.formexportacao')
        </div>

        <!-- Tab: Configurações -->
        <div class="tab-pane fade" id="config-tab-pane" role="tabpanel" aria-labelledby="config-tab" tabindex="0">
            <div class="p-3">
                <p>Configurações gerais da empresa podem ser gerenciadas aqui.</p>
                <button class="btn btn-primary">Editar Configurações</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset("js/dataTables.js") }}"></script>
<script>
    $(".table").DataTable({
        language: {
            url: '{{ asset("js/json/data_Table_pt_br.json") }}'
        },
        responsive: true,
        autoWidth: false
    });
</script>
@endsection