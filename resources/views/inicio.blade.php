@extends('layouts.app')

@section('titulo', 'Inicio · Café de Barrio')

@section('pantalla-completa')
    <section class="relative flex min-h-[calc(100vh-64px)] items-center bg-cover bg-center"
             style="background-image: url('{{ asset('img/hero-cafe.jpg') }}')">

        {{-- capa oscura para que el texto se lea bien --}}
        <div class="absolute inset-0 bg-black/40"></div>

        <div class="relative mx-auto w-full max-w-6xl px-12">
            <div class="max-w-2xl">
                <h1 class="text-5xl font-bold leading-tight text-white md:text-6xl">
                    Disfruta el arte de un buen café
                </h1>

                <p class="mt-10 text-xl font-semibold leading-snug text-white md:text-2xl">
                    Seleccionamos granos de especialidad tostados a la perfección para
                    transformar tus mañanas. Ven a descubrir el verdadero sabor del café
                    artesanal en un ambiente diseñado para inspirarte.
                </p>

                <a href="{{ route('menu.index') }}"
                   class="mt-10 inline-block rounded-full bg-black px-6 py-3 text-2xl font-semibold text-white hover:bg-neutral-800">
                    Ver menú
                </a>
            </div>
        </div>
    </section>
@endsection