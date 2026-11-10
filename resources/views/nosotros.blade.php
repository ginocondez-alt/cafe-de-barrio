@extends('layouts.app')

@section('titulo', 'Nosotros · Café de Barrio')

@section('contenido')
    <h1 class="mb-4 text-center text-xl font-semibold">Nosotros</h1>

    <p class="mx-auto mb-8 max-w-2xl text-center text-neutral-600">
        Somos una cafetería de barrio con granos de especialidad y ambiente cálido. 
        Nos apasiona ofrecer a nuestros clientes una experiencia única, 
        desde el aroma del café recién molido hasta la atención personalizada de nuestro equipo. 
        Ven y disfruta de un momento de relax en nuestro local.
    </p>

    <div class="mx-auto max-w-md rounded-2xl border border-neutral-200 bg-white p-6">
        <h2 class="mb-3 font-semibold">Horarios de atención</h2>

        <ul class="divide-y divide-neutral-100 text-sm">
            @foreach ($horarios as $horario)
                <li class="flex justify-between py-2">
                    <span class="font-medium">{{ $horario->nombreDia() }}</span>
                    <span class="{{ $horario->activo ? 'text-neutral-600' : 'text-neutral-400' }}">
                        {{ $horario->rango() }}
                    </span>
                </li>
            @endforeach
        </ul>
    </div>
@endsection