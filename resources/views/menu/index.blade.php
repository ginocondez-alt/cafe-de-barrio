@extends('layouts.app')

@section('titulo', 'Menú · Café de Barrio')

@section('contenido')
    <h1 class="mb-5 text-center text-xl font-semibold">Nuestro menú</h1>

    <form method="GET" action="{{ route('menu.index') }}" class="mb-4 flex gap-2">
        <input type="text" name="buscar" placeholder="Buscar por nombre..."
               value="{{ request('buscar') }}"
               class="flex-1 rounded-full bg-white px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-neutral-900">
        <button type="submit" class="rounded-full bg-black px-5 py-2.5 text-sm text-white">
            Buscar
        </button>
    </form>

    <nav class="mb-6 flex flex-wrap gap-2 text-xs">
        <a href="{{ route('menu.index') }}"
           class="rounded-full px-3.5 py-1 {{ request('categoria') ? 'bg-white' : 'bg-black text-white' }}">
            Todas
        </a>

        @foreach ($categorias as $categoria)
            <a href="{{ route('menu.index', ['categoria' => $categoria->id]) }}"
               class="rounded-full px-3.5 py-1 {{ request('categoria') == $categoria->id ? 'bg-black text-white' : 'bg-white' }}">
                {{ $categoria->nombre }}
            </a>
        @endforeach
    </nav>

    <div class="grid gap-4 md:grid-cols-2">
        @forelse ($productos as $producto)
            <article class="overflow-hidden rounded-2xl border border-neutral-200 bg-white">
                @if ($producto->imagen)
                    <img src="{{ asset('img/productos/' . $producto->imagen) }}"
                         alt="{{ $producto->nombre }}" class="h-32 w-full object-cover">
                @else
                    <div class="h-32 bg-neutral-300"></div>
                @endif

                <div class="p-4">
                    <h3 class="font-semibold">{{ $producto->nombre }}</h3>
                    <span class="text-xs text-neutral-500">{{ $producto->categoria->nombre }}</span>
                    <p class="my-1.5 text-sm text-neutral-600">{{ $producto->descripcion }}</p>
                    <span class="font-bold">${{ number_format($producto->precio, 0, ',', '.') }}</span>
                </div>
            </article>
        @empty
            <p>No hay productos para mostrar.</p>
        @endforelse
    </div>
@endsection