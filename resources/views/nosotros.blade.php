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
    {{--formulario de contacto--}}
     <div class="mx-auto mt-10 max-w-2xl rounded-2xl border border-neutral-200 bg-white p-6 md:p-8">

        <div class="mb-6 text-center">
            <h2 class="text-xl font-semibold">¡Queremos escucharte!</h2>
            <p class="mt-2 text-sm text-neutral-600">
                Tu opinión nos ayuda a mejorar. Envianos tus consultas,
                sugerencias, quejas o pedidos especiales.
            </p>
        </div>

        <form action="{{ route('solicitudes.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Mensaje de éxito --}}
            @if (session('success'))
                <div class="rounded-lg bg-green-100 p-3 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Errores de validación --}}
            @if ($errors->any())
                <div class="rounded-lg bg-red-100 p-3 text-red-800">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label for="tipo" class="mb-1 block text-sm font-medium">
                    Tipo de solicitud
                </label>
                <select id="tipo" name="tipo" required
                    class="w-full rounded-lg border border-neutral-300 p-3 focus:border-neutral-800 focus:outline-none">
                    <option value="">Seleccioná una opción</option>
                    <option value="queja" {{ old('tipo') == 'queja' ? 'selected' : '' }}>Queja o reclamo</option>
                    <option value="sugerencia" {{ old('tipo') == 'sugerencia' ? 'selected' : '' }}>Sugerencia</option>
                    <option value="pedido_especial" {{ old('tipo') == 'pedido_especial' ? 'selected' : '' }}>Pedido especial</option>
                    <option value="consulta" {{ old('tipo') == 'consulta' ? 'selected' : '' }}>Otra consulta</option>
                </select>
            </div>

            <div>
                <label for="nombre" class="mb-1 block text-sm font-medium">
                    Nombre y apellido
                </label>
                <input type="text" id="nombre" name="nombre" required
                    value="{{ old('nombre') }}"
                    placeholder="Ingresá tu nombre"
                    class="w-full rounded-lg border border-neutral-300 p-3 focus:border-neutral-800 focus:outline-none">
            </div>

            <div>
                <label for="email" class="mb-1 block text-sm font-medium">
                    Correo electrónico
                </label>
                <input type="email" id="email" name="email" required
                    value="{{ old('email') }}"
                    placeholder="correo@ejemplo.com"
                    class="w-full rounded-lg border border-neutral-300 p-3 focus:border-neutral-800 focus:outline-none">
            </div>

            <div>
                <label for="asunto" class="mb-1 block text-sm font-medium">
                    Asunto
                </label>
                <input type="text" id="asunto" name="asunto" required
                    value="{{ old('asunto') }}"
                    placeholder="Motivo de tu consulta"
                    class="w-full rounded-lg border border-neutral-300 p-3 focus:border-neutral-800 focus:outline-none">
            </div>

            <div>
                <label for="mensaje" class="mb-1 block text-sm font-medium">
                    Mensaje
                </label>
                <textarea id="mensaje" name="mensaje" rows="5" required
                    placeholder="Contanos cómo podemos ayudarte..."
                    class="w-full rounded-lg border border-neutral-300 p-3 focus:border-neutral-800 focus:outline-none">{{ old('mensaje') }}</textarea>
            </div>

            <button type="submit"
                class="w-full rounded-lg bg-neutral-900 px-5 py-3 font-medium text-white transition hover:bg-neutral-700">
                Enviar solicitud
            </button>

        </form>
    </div>
@endsection