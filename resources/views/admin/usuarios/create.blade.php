@extends('layouts.admin')

@section('titulo', 'Nuevo usuario · Café de Barrio')

@section('panel')
    <h1 class="mb-4 text-xl font-semibold">Nuevo usuario</h1>

    <form method="POST" action="{{ route('admin.usuarios.store') }}"
          class="max-w-lg space-y-4 rounded-2xl border border-neutral-200 bg-white p-6">
        @csrf
        @include('admin.usuarios._form')
    </form>
@endsection