@extends('layouts.admin')

@section('titulo', 'Gestión de menú · Café de Barrio')

@section('panel')
    <div class="mb-4 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-semibold">Gestión de menú</h1>
            <p class="text-xs text-neutral-500">{{ $productos->count() }} productos</p>
        </div>
        <a href="{{ route('admin.productos.create') }}"
           class="rounded-lg bg-black px-4 py-2 text-sm text-white">+ Nuevo producto</a>
    </div>

    <form method="GET" class="mb-4 flex gap-2">
        <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar producto..."
               class="flex-1 rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-neutral-900">
        <select name="categoria" class="rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm">
            <option value="">Todas las categorías</option>
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}" @selected(request('categoria') == $categoria->id)>
                    {{ $categoria->nombre }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="rounded-lg bg-black px-4 py-2 text-sm text-white">Filtrar</button>
    </form>

    <div class="overflow-hidden rounded-2xl border border-neutral-200 bg-white">
        <table class="w-full text-sm">
            <thead class="bg-neutral-50 text-left text-xs uppercase text-neutral-500">
                <tr>
                    <th class="px-4 py-3">Nombre</th>
                    <th class="px-4 py-3">Categoría</th>
                    <th class="px-4 py-3">Precio</th>
                    <th class="px-4 py-3">Descripción</th>
                    <th class="px-4 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($productos as $producto)
                    <tr class="border-t border-neutral-100">
                        <td class="px-4 py-3 font-medium">{{ $producto->nombre }}</td>
                        <td class="px-4 py-3">{{ $producto->categoria->nombre }}</td>
                        <td class="px-4 py-3 font-semibold">${{ number_format($producto->precio, 0, ',', '.') }}</td>
                        <td class="max-w-48 truncate px-4 py-3 text-neutral-500">{{ $producto->descripcion }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('admin.productos.edit', $producto) }}" class="text-blue-600">Editar</a>
                                <form method="POST" action="{{ route('admin.productos.destroy', $producto) }}"
                                      onsubmit="return confirm('¿Eliminar este producto?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-neutral-500">No hay productos.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection