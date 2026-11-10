@extends('layouts.admin')

@section('titulo', 'Panel · Café de Barrio')

@section('panel')
    <h1 class="mb-4 text-xl font-semibold">Dashboard</h1>
    <p class="text-sm text-neutral-600">Bienvenido, {{ auth()->user()->name }}.</p>
@endsection