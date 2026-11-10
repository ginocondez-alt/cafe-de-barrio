@php $u = $usuario ?? null; @endphp

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="mb-1 block text-xs font-semibold">Nombre</label>
        <input type="text" name="name" value="{{ old('name', $u->name ?? '') }}"
               class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-neutral-900">
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold">Apellido</label>
        <input type="text" name="apellido" value="{{ old('apellido', $u->apellido ?? '') }}"
               class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-neutral-900">
    </div>
</div>

<div>
    <label class="mb-1 block text-xs font-semibold">Email</label>
    <input type="email" name="email" value="{{ old('email', $u->email ?? '') }}"
           class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-neutral-900">
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="mb-1 block text-xs font-semibold">Dirección</label>
        <input type="text" name="direccion" value="{{ old('direccion', $u->direccion ?? '') }}"
               class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-neutral-900">
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold">Teléfono</label>
        <input type="text" name="telefono" value="{{ old('telefono', $u->telefono ?? '') }}"
               class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-neutral-900">
    </div>
</div>

<div>
    <label class="mb-1 block text-xs font-semibold">Rol</label>
    <select name="rol" @disabled($u && $u->id === auth()->id())
            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-neutral-900">
        @foreach ($roles as $rol)
            <option value="{{ $rol }}" @selected(old('rol', $u->rol ?? 'caja') == $rol)>{{ ucfirst($rol) }}</option>
        @endforeach
    </select>
    @if ($u && $u->id === auth()->id())
        <input type="hidden" name="rol" value="{{ $u->rol }}">
        <p class="mt-1 text-xs text-neutral-500">No podés cambiar tu propio rol.</p>
    @endif
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="mb-1 block text-xs font-semibold">
            Contraseña @if ($u) <span class="font-normal text-neutral-500">(vacía = no cambia)</span> @endif
        </label>
        <input type="password" name="password"
               class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-neutral-900">
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold">Repetir contraseña</label>
        <input type="password" name="password_confirmation"
               class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-neutral-900">
    </div>
</div>

<div class="flex gap-3">
    <button type="submit" class="rounded-lg bg-black px-5 py-2.5 text-sm font-semibold text-white">Guardar</button>
    <a href="{{ route('admin.usuarios.index') }}" class="rounded-lg border border-neutral-300 px-5 py-2.5 text-sm">Cancelar</a>
</div>