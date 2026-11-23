@extends('layouts.app')

@section('titulo', 'Cocina · Café de Barrio')

@section('contenido')
    @include('partials.exito')
    @include('partials.errores')

    <h1 class="mb-5 text-center text-xl font-semibold">Cocina</h1>
    <p class="mb-6 text-center text-sm text-neutral-500">Pedidos confirmados y en preparación.</p>

    @forelse ($pedidos as $pedido)
        <section class="mb-4 overflow-hidden rounded-2xl border border-neutral-200 bg-white">
            <header class="flex flex-wrap items-center justify-between gap-2 border-b border-neutral-100 px-4 py-3">
                <div>
                    <h2 class="font-semibold">Pedido #{{ $pedido->id }}</h2>
                    <p class="text-xs text-neutral-500">
                        {{ $pedido->user->name }} {{ $pedido->user->apellido }} · {{ $pedido->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>
                <span @class([
                    'rounded-full px-2.5 py-0.5 text-xs',
                    'bg-amber-100 text-amber-800' => $pedido->estado === \App\Models\Pedido::CONFIRMADO,
                    'bg-blue-100 text-blue-800' => $pedido->estado === \App\Models\Pedido::PREPARANDO,
                ])>
                    {{ $pedido->estadoTexto() }}
                </span>
            </header>

            <ul class="divide-y divide-neutral-100">
                @foreach ($pedido->items as $item)
                    <li class="flex items-center gap-3 px-4 py-3">
                        @if ($item->imagen)
                            <img src="{{ Storage::url($item->imagen) }}"
                                 alt="{{ $item->nombre }}" class="h-12 w-12 rounded-lg object-cover">
                        @else
                            <div class="h-12 w-12 rounded-lg bg-neutral-300"></div>
                        @endif

                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium">{{ $item->nombre }}</p>
                            <p class="text-xs text-neutral-500">
                                {{ $item->cantidad }} × ${{ number_format((float) $item->precio_unitario, 0, ',', '.') }}
                            </p>
                        </div>

                        <span class="text-sm font-semibold">
                            ${{ number_format($item->subtotal(), 0, ',', '.') }}
                        </span>
                    </li>
                @endforeach
            </ul>

            <footer class="flex flex-wrap items-center justify-between gap-3 border-t border-neutral-100 px-4 py-3">
                <div>
                    <span class="text-xs text-neutral-500">Total</span>
                    <p class="font-bold">${{ number_format($pedido->subtotal(), 0, ',', '.') }}</p>
                </div>

                <div class="flex flex-wrap gap-2">
                    @if ($pedido->estado === \App\Models\Pedido::CONFIRMADO)
                        <form method="POST" action="{{ route('cocina.pedidos.estado', $pedido) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="estado" value="{{ \App\Models\Pedido::PREPARANDO }}">
                            <button type="submit"
                                    class="rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                                Preparando
                            </button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('cocina.pedidos.estado', $pedido) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="estado" value="{{ \App\Models\Pedido::FINALIZADO }}">
                        <button type="submit"
                                class="rounded-full bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">
                            Entregar
                        </button>
                    </form>

                    <form method="POST" action="{{ route('cocina.pedidos.estado', $pedido) }}"
                          onsubmit="return confirm('¿Cancelar este pedido?')">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="estado" value="{{ \App\Models\Pedido::CANCELADO }}">
                        <button type="submit"
                                class="rounded-full border border-red-600 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50">
                            Cancelar
                        </button>
                    </form>
                </div>
            </footer>
        </section>
    @empty
        <div class="rounded-2xl border border-neutral-200 bg-white p-8 text-center">
            <p class="text-neutral-600">No hay pedidos para preparar.</p>
        </div>
    @endforelse
@endsection
