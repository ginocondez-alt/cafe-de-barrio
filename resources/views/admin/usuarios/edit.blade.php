@extends('layouts.admin')

@section('titulo', 'Editar usuario · Café de Barrio')

@section('panel')
    <h1 class="mb-4 text-xl font-semibold">Editar usuario</h1>

    <form method="POST" action="{{ route('admin.usuarios.update', $usuario) }}"
          class="max-w-lg space-y-4 rounded-2xl border border-neutral-200 bg-white p-6">
        @csrf
        @method('PUT')
        @include('admin.usuarios._form')
    </form>
@endsection