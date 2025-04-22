@extends('admin._theme')

@section('title', 'Escritórios')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dataTables.css') }}" />
@endsection

@section('content')
    <div class="container mt-5">
        <h2 class="text-center">Escritórios</h2>
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('escritorios.create') }}" class="btn btn-primary">+ Adicionar</a>
        </div>

        @if ($escritorios->isEmpty())
            <p class="text-center">Nenhum escritório cadastrado.</p>
        @else
            <table class="table table-striped stripe row-border order-column w-100">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>CNPJ</th>
                        <th>Status</th>
                        <th>Cadastro</th>
                        <th class="text-center">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($escritorios as $e)
                        <tr>
                            <td>{{ $e->id }}</td>
                            <td>{{ $e->name }}</td>
                            <td>{{ $e->cnpj }}</td>
                            <td>{{ $e->active ? 'Ativo' : 'Desativado' }}</td>
                            <td>{{ $e->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                <a href="{{ route('escritorios.edit', $e) }}" class="btn btn-sm btn-warning">
                                    <i class="fa-solid fa-pen-to-square text-white"></i>
                                </a>
                                <form action="{{ route('escritorios.destroy', $e) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                                <a href="{{ route('escritorios.show', $e) }}" class="btn btn-sm btn-info">
                                    <i class="fa-solid fa-eye text-white"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/dataTables.js') }}"></script>
    <script src="{{ asset('js/dataTables.fixedColumns.js') }}"></script>
    <script>
        $('.table').DataTable({
            language: { url: '{{ asset("js/json/data_Table_pt_br.json") }}' },
            fixedColumns: { start: 0, end: 1 },
            scrollX: true
        });
    </script>
@endsection