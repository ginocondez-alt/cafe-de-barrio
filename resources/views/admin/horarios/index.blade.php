@extends('layouts.admin')

@section('titulo', 'Disponibilidad del local · Café de Barrio')

@section('panel')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-xl font-semibold">Disponibilidad del local</h1>
        <a href="{{ route('admin.horarios.create') }}"
           class="rounded-lg bg-black px-4 py-2 text-sm text-white">+ Agregar horario</a>
    </div>

    <div class="overflow-hidden rounded-2xl border border-neutral-200 bg-white">
        <table class="w-full text-sm">
            <tbody>
                @forelse ($horarios as $horario)
                    <tr class="border-t border-neutral-100 first:border-t-0">
                        <td class="px-4 py-3 font-semibold">{{ $horario->nombreDia() }}</td>
                        <td class="px-4 py-3 text-neutral-600">{{ $horario->rango() }}</td>
                        <td class="px-4 py-3">
                            @if ($horario->activo)
                                <span class="rounded-full bg-green-100 px-2.5 py-0.5 text-xs text-green-800">Activo</span>
                            @else
                                <span class="rounded-full bg-neutral-200 px-2.5 py-0.5 text-xs text-neutral-600">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('admin.horarios.edit', $horario) }}" class="text-blue-600">Editar</a>
                                <form method="POST" action="{{ route('admin.horarios.destroy', $horario) }}"
                                      onsubmit="return confirm('¿Eliminar este horario?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td class="px-4 py-6 text-center text-neutral-500">No hay horarios cargados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection