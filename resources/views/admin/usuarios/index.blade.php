@extends('layouts.admin')

@section('titulo', 'Usuarios · Café de Barrio')

@section('panel')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-xl font-semibold">Usuarios</h1>
        <a href="{{ route('admin.usuarios.create') }}"
           class="rounded-lg bg-black px-4 py-2 text-sm text-white">+ Nuevo usuario</a>
    </div>

    <form method="GET" class="mb-4 flex gap-2">
        <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por nombre o email..."
               class="flex-1 rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-neutral-900">
        <select name="rol" class="rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm">
            <option value="">Todos los roles</option>
            @foreach ($roles as $rol)
                <option value="{{ $rol }}" @selected(request('rol') == $rol)>{{ ucfirst($rol) }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-lg bg-black px-4 py-2 text-sm text-white">Filtrar</button>
    </form>

    <div class="overflow-hidden rounded-2xl border border-neutral-200 bg-white">
        <table class="w-full text-sm">
            <thead class="bg-neutral-50 text-left text-xs uppercase text-neutral-500">
                <tr>
                    <th class="px-4 py-3">Nombre</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Rol</th>
                    <th class="px-4 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($usuarios as $usuario)
                    <tr class="border-t border-neutral-100">
                        <td class="px-4 py-3 font-medium">{{ $usuario->name }} {{ $usuario->apellido }}</td>
                        <td class="px-4 py-3 text-neutral-600">{{ $usuario->email }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full bg-neutral-100 px-2.5 py-0.5 text-xs">{{ ucfirst($usuario->rol) }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="text-blue-600">Editar</a>
                                @if ($usuario->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.usuarios.destroy', $usuario) }}"
                                          onsubmit="return confirm('¿Eliminar este usuario?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600">Eliminar</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-6 text-center text-neutral-500">No hay usuarios.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection