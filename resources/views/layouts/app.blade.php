<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', 'Café de Barrio')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Pacifico&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral-100 font-sans text-neutral-900">

    <header class="flex items-center justify-between bg-neutral-900 px-12 py-3.5">
    <a href="{{ route('inicio') }}" class="font-logo text-2xl text-white">Cafe de Barrio</a>

    <nav class="flex gap-8 text-sm">
        <a href="{{ route('inicio') }}" class="{{ request()->routeIs('inicio') ? 'text-blue-400' : 'text-white' }}">Inicio</a>
        <a href="{{ route('menu.index') }}" class="{{ request()->routeIs('menu.*') ? 'text-blue-400' : 'text-white' }}">Menú</a>
        <a href="{{ route('nosotros') }}" class="{{ request()->routeIs('nosotros') ? 'text-blue-400' : 'text-white' }}">Nosotros</a>
        @auth
            <a href="#" class="text-white">Mis pedidos</a>
        @endauth
        @auth
    @if (auth()->user()->tieneRol('administrador'))
        <a href="{{ route('admin.dashboard') }}"
           class="{{ request()->routeIs('admin.*') ? 'text-blue-400' : 'text-white' }}">Administrar</a>
    @endif
@endauth
    </nav>

    <div class="flex items-center gap-2 text-xs">
        @guest
            <a href="{{ route('login') }}" class="rounded-full border border-blue-400 px-4 py-1.5 text-blue-400">Acceso</a>
            <a href="{{ route('registro') }}" class="rounded-full border border-blue-400 px-4 py-1.5 text-blue-400">Registrarse</a>
        @endguest

        @auth
            <span class="text-white">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="rounded-full border border-blue-400 px-4 py-1.5 text-blue-400">Salir</button>
            </form>
        @endauth
    </div>
</header>

    @hasSection('pantalla-completa')
    @yield('pantalla-completa')
@else
    <main class="mx-auto max-w-4xl px-6 py-8">
        @yield('contenido')
    </main>
@endif

</body>
</html>