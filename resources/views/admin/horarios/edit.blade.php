@extends('layouts.admin')

@section('titulo', 'Editar horario · Café de Barrio')

@section('panel')
    <h1 class="mb-4 text-xl font-semibold">Editar horario</h1>

    <form method="POST" action="{{ route('admin.horarios.update', $horario) }}"
          class="max-w-lg space-y-4 rounded-2xl border border-neutral-200 bg-white p-6">
        @csrf
        @method('PUT')
        @include('admin.horarios._form')
    </form>
@endsection