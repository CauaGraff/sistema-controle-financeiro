@extends('_theme')

@section('css')
<link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}" />
@endsection

@section('content')
<div class="container mt-5">
    <h2 class="text-center">Registrar Baixa de Lançamento</h2>
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <!-- Formulário para Baixa -->
            <form action="{{ route('lancamentos.pagamentos.baixa.store', $lancamento->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <!-- ID do Lançamento (hidden) -->
                <input type="hidden" name="id_lancamento" value="{{ $lancamento->id }}">

                <div class="form-group mb-3">
                    <label for="valor">Valor Pago:</label>
                    <input type="number" step="0.01" name="valor" class="form-control" value="{{ old('valor') }}">
                    @error('valor')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="juros">Juros:</label>
                    <input type="number" step="0.01" name="juros" class="form-control" value="{{ old('juros') }}">
                    @error('juros')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="multa">Multa:</label>
                    <input type="number" step="0.01" name="multa" class="form-control" value="{{ old('multa') }}">
                    @error('multa')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="desconto">Desconto:</label>
                    <input type="number" step="0.01" name="desconto" class="form-control" value="{{ old('desconto') }}">
                    @error('desconto')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="anexo">Anexo (Comprovante):</label>
                    <input type="file" name="anexo" class="form-control">
                    @error('anexo')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group text-center">
                    <button type="submit" class="btn btn-success">Registrar Baixa</button>
                    <a href="{{ route('lancamentos.pagamentos.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('js/toastr.min.js') }}"></script>
<script>
    // Exibindo mensagens de sucesso ou erro usando o Toastr
    @if(session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if(session('error'))
        toastr.error("{{ session('error') }}");
    @endif
</script>
@endsection