@extends('layouts.admin')

@section('titulo', 'Editar categoría · Café de Barrio')

@section('panel')
    <h1 class="mb-4 text-xl font-semibold">Editar categoría</h1>

    <form method="POST" action="{{ route('admin.categorias.update', $categoria) }}"
          class="max-w-md space-y-4 rounded-2xl border border-neutral-200 bg-white p-6">
        @csrf
        @method('PUT')
        @include('admin.categorias._form')
    </form>
@endsection