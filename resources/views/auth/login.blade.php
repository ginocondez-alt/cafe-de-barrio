@extends('layouts.app')

@section('titulo', 'Ingresar · Café de Barrio')

@section('contenido')
    <div class="mx-auto max-w-md rounded-2xl border border-neutral-200 bg-white p-8">
        <h1 class="mb-6 text-center text-xl font-semibold">Ingresar a tu cuenta</h1>

        @include('partials.errores')

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label class="mb-1 block text-xs font-semibold">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-neutral-900">
            </div>

            <div>
                <label class="mb-1 block text-xs font-semibold">Contraseña</label>
                <input type="password" name="password"
                       class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-neutral-900">
            </div>

            <button type="submit" class="w-full rounded-lg bg-black py-2.5 text-sm font-semibold text-white">
                Ingresar
            </button>

            <p class="text-center text-xs text-neutral-500">
                ¿No tenés cuenta?
                <a href="{{ route('registro') }}" class="text-blue-500">Registrate acá</a>
            </p>
        </form>
    </div>
@endsection