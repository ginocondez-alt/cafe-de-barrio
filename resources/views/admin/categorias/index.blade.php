@extends('layouts.admin')

@section('titulo', 'Categorías · Café de Barrio')

@section('panel')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-xl font-semibold">Categorías</h1>
        <a href="{{ route('admin.categorias.create') }}"
           class="rounded-lg bg-black px-4 py-2 text-sm text-white">+ Nueva categoría</a>
    </div>

    <div class="overflow-hidden rounded-2xl border border-neutral-200 bg-white">
        <table class="w-full text-sm">
            <thead class="bg-neutral-50 text-left text-xs uppercase text-neutral-500">
                <tr>
                    <th class="px-4 py-3">Nombre</th>
                    <th class="px-4 py-3">Productos</th>
                    <th class="px-4 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categorias as $categoria)
                    <tr class="border-t border-neutral-100">
                        <td class="px-4 py-3 font-medium">{{ $categoria->nombre }}</td>
                        <td class="px-4 py-3">{{ $categoria->productos_count }}</td>
                        <td class="flex justify-end gap-3 px-4 py-3">
                            <a href="{{ route('admin.categorias.edit', $categoria) }}"
                               class="text-blue-600">Editar</a>

                            <form method="POST"
                                  action="{{ route('admin.categorias.destroy', $categoria) }}"
                                  onsubmit="return confirm('¿Eliminar esta categoría?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-4 py-6 text-center text-neutral-500">No hay categorías.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection