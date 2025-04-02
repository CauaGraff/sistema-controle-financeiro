<tr>
    <td>{{ $numero }}</td>
    <td>{!! str_repeat('-- ', $nivel) !!}{{ $categoria->descricao }}</td>
    <td class="text-center">
        <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn btn-warning btn-sm">
            <i class="fa-solid fa-pen-to-square" style="color: white;"></i>
        </a>
        <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
        </form>
    </td>
</tr>

@php
    $numSub = 1;
@endphp

@foreach ($categoria->subcategorias as $subcategoria)
    @include('categorias._categoria', ['categoria' => $subcategoria, 'numero' => "$numero.$numSub", 'nivel' => $nivel + 1])
    @php
        $numSub++;
    @endphp
@endforeach