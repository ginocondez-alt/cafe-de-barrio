@extends('layouts.app')

@section('titulo', 'Mis pedidos · Café de Barrio')

@section('contenido')
    @include('partials.exito')
    @include('partials.errores')

    <h1 class="mb-5 text-center text-xl font-semibold">Mis pedidos</h1>

    @if ($pedidos->contains('estado', \App\Models\Pedido::EN_CAJA))
        <div class="mb-4 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            Tenés pedidos en caja. Te los entregan en el mostrador.
        </div>
    @endif

    @forelse ($pedidos as $pedido)
        <section class="mb-4 overflow-hidden rounded-2xl border border-neutral-200 bg-white">
            <header class="flex flex-wrap items-center justify-between gap-2 border-b border-neutral-100 px-4 py-3">
                <div>
                    <h2 class="font-semibold">Pedido #{{ $pedido->id }}</h2>
                    <span class="text-xs text-neutral-500">{{ $pedido->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <span @class([
                    'rounded-full px-2.5 py-0.5 text-xs',
                    'bg-neutral-200 text-neutral-600' => $pedido->estado === \App\Models\Pedido::PENDIENTE,
                    'bg-amber-100 text-amber-800' => $pedido->estado === \App\Models\Pedido::CONFIRMADO,
                    'bg-blue-100 text-blue-800' => $pedido->estado === \App\Models\Pedido::PREPARANDO,
                    'bg-indigo-100 text-indigo-800' => $pedido->estado === \App\Models\Pedido::EN_CAJA,
                    'bg-green-100 text-green-800' => $pedido->estado === \App\Models\Pedido::RETIRADO,
                    'bg-red-100 text-red-800' => $pedido->estado === \App\Models\Pedido::CANCELADO,
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

                        @if ($pedido->esPendiente())
                            <form method="POST" action="{{ route('pedidos.quitar', $item) }}"
                                  onsubmit="return confirm('¿Quitar este producto del pedido?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="rounded-full border border-neutral-300 px-3 py-1 text-xs text-neutral-600 hover:border-red-600 hover:text-red-600">
                                    Quitar
                                </button>
                            </form>
                        @endif
                    </li>
                @endforeach
            </ul>

            <footer class="flex flex-wrap items-center justify-between gap-3 border-t border-neutral-100 px-4 py-3">
                <div>
                    <span class="text-xs text-neutral-500">Total</span>
                    <p class="font-bold">${{ number_format($pedido->subtotal(), 0, ',', '.') }}</p>
                </div>

                <div class="flex flex-wrap gap-2">
                    @if ($pedido->esPendiente() && $pedido->items->isNotEmpty())
                        <form method="POST" action="{{ route('pedidos.confirmar', $pedido) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="rounded-full bg-black px-5 py-2.5 text-sm font-semibold text-white hover:bg-neutral-800">
                                Confirmar pedido
                            </button>
                        </form>
                    @endif

                    @if ($pedido->esCerrado())
                        <form method="POST" action="{{ route('pedidos.destroy', $pedido) }}"
                              onsubmit="return confirm('¿Borrar este pedido de tu historial?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="rounded-full border border-neutral-300 px-5 py-2.5 text-sm font-semibold text-neutral-700 hover:border-red-600 hover:text-red-600">
                                Borrar pedido
                            </button>
                        </form>
                    @endif
                </div>
            </footer>
        </section>
    @empty
        <div class="rounded-2xl border border-neutral-200 bg-white p-8 text-center">
            <p class="text-neutral-600">Todavía no hiciste ningún pedido.</p>
            <a href="{{ route('menu.index') }}"
               class="mt-4 inline-block rounded-full bg-black px-5 py-2.5 text-sm font-semibold text-white hover:bg-neutral-800">
                Ver el menú
            </a>
        </div>
    @endforelse
@endsection
