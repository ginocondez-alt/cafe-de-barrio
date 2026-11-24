@extends('layouts.admin')

@section('panel')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold">Solicitudes</h1>
        <p class="mt-1 text-sm text-neutral-600">
            Consultá los reclamos, sugerencias, pedidos especiales y consultas de los clientes.
        </p>
    </div>

    <div class="overflow-x-auto rounded-xl border border-neutral-200 bg-white">
        <table class="w-full text-left text-sm">
            <thead class="bg-neutral-100 text-neutral-600">
                <tr>
                    <th class="px-4 py-3">Tipo</th>
                    <th class="px-4 py-3">Nombre</th>
                    <th class="px-4 py-3">Asunto</th>
                    <th class="px-4 py-3">Fecha</th>
                    <th class="px-4 py-3">Estado</th>
                    <th class="px-4 py-3">Mensaje</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-neutral-100">
                @forelse ($solicitudes as $solicitud)
                    <tr>
                        <td class="px-4 py-3">
                            {{ ucfirst(str_replace('_', ' ', $solicitud->tipo)) }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $solicitud->nombre }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $solicitud->asunto }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $solicitud->created_at->format('d/m/Y') }}
                        </td>

                        <td class="px-4 py-3">
                            <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-800">
                                {{ ucfirst($solicitud->estado) }}
                            </span>
                        </td>

                        <td class="px-4 py-3">
                            {{ $solicitud->mensaje }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-neutral-500">
                            Todavía no hay solicitudes recibidas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection