@extends('admin._theme')

@section('title', 'Detalhes do Escritório')

@section('content')
    <div class="container mt-5">
        <h3>Escritório: {{ $escritorio->name }}</h3>
        <ul class="list-group mt-3">
            <li class="list-group-item"><strong>CNPJ:</strong> {{ $escritorio->cnpj }}</li>
            <li class="list-group-item"><strong>CEP:</strong> {{ $escritorio->cep }}</li>
            <li class="list-group-item"><strong>Endereço:</strong>
                {{ "{$escritorio->rua}, {$escritorio->bairro}, {$escritorio->cidade} - {$escritorio->uf}" }}
            </li>
            <li class="list-group-item"><strong>Complemento:</strong> {{ $escritorio->complemento }}</li>
            <li class="list-group-item"><strong>Observações:</strong> {{ $escritorio->obs }}</li>
            <li class="list-group-item"><strong>Status:</strong> {{ $escritorio->active ? 'Ativo' : 'Desativado' }}</li>
            <li class="list-group-item"><strong>Criado em:</strong> {{ $escritorio->created_at->format('d/m/Y H:i') }}</li>
        </ul>
        <div class="mt-3">
            <a href="{{ route('escritorios.index') }}" class="btn btn-secondary">Voltar</a>
        </div>
    </div>
@endsection