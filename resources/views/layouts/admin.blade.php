@extends('layouts.app')

@section('contenido')
    <div class="flex gap-6">
        <aside class="w-44 shrink-0 space-y-1 text-sm">
            @php
                $enlaces = [
                    ['admin.dashboard',        'Dashboard'],
                    ['admin.categorias.index', 'Categorías'],
                    ['admin.productos.index',  'Menú'],
                    ['admin.usuarios.index', 'Usuarios'],
                    ['admin.horarios.index', 'Horarios'],
                ];
            @endphp

            @foreach ($enlaces as [$ruta, $texto])
                <a href="{{ route($ruta) }}"
                   class="block rounded-lg px-3 py-2 {{ request()->routeIs($ruta) ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:bg-neutral-200' }}">
                    {{ $texto }}
                </a>
            @endforeach
        </aside>

        <section class="min-w-0 flex-1">
            @if (session('exito'))
                <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800">
                    {{ session('exito') }}
                </div>
            @endif

            @include('partials.errores')

            @yield('panel')
        </section>
    </div>
@endsection