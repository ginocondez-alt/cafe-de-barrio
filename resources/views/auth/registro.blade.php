@extends('layouts.app')

@section('titulo', 'Crear una cuenta · Café de Barrio')

@section('contenido')
    <div class="mx-auto max-w-lg rounded-2xl border border-neutral-200 bg-white p-8">
        <h1 class="mb-6 text-center text-xl font-semibold">Crear una cuenta</h1>

        @include('partials.errores')

        <form method="POST" action="{{ route('registro') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="mb-1 block text-xs font-semibold">Nombre</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-neutral-900">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold">Apellido</label>
                    <input type="text" name="apellido" value="{{ old('apellido') }}"
                           class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-neutral-900">
                </div>
            </div>

            <div>
                <label class="mb-1 block text-xs font-semibold">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-neutral-900">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="mb-1 block text-xs font-semibold">Dirección</label>
                    <input type="text" name="direccion" value="{{ old('direccion') }}"
                           class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-neutral-900">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold">Teléfono de contacto</label>
                    <input type="text" name="telefono" value="{{ old('telefono') }}"
                           class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-neutral-900">
                </div>
            </div>

            <div>
                <label class="mb-1 block text-xs font-semibold">Contraseña</label>
                <input type="password" name="password"
                       class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-neutral-900">
            </div>

            <div>
                <label class="mb-1 block text-xs font-semibold">Repetir contraseña</label>
                <input type="password" name="password_confirmation"
                       class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-neutral-900">
            </div>

            <button type="submit" class="w-full rounded-lg bg-black py-2.5 text-sm font-semibold text-white">
                Registrarse
            </button>

            <p class="text-center text-xs text-neutral-500">
                ¿Ya tienes cuenta?
                <a href="{{ route('login') }}" class="text-blue-500">Ingresa acá</a>
            </p>
        </form>
    </div>
@endsection