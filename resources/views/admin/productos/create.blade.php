@extends('layouts.admin')

@section('titulo', 'Nuevo producto · Café de Barrio')

@section('panel')
    <h1 class="mb-4 text-xl font-semibold">Nuevo producto</h1>

    <form method="POST" action="{{ route('admin.productos.store') }}"
          class="max-w-lg space-y-4 rounded-2xl border border-neutral-200 bg-white p-6">
        @csrf
        @include('admin.productos._form')
    </form>
@endsection