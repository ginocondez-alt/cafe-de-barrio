@php $h = $horario ?? null; @endphp

<div>
    <label class="mb-1 block text-xs font-semibold">Día</label>
    <select name="dia"
            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-neutral-900">
        @foreach (\App\Models\Horario::DIAS as $numero => $nombre)
            <option value="{{ $numero }}" @selected(old('dia', $h->dia ?? '') == $numero)>{{ $nombre }}</option>
        @endforeach
    </select>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="mb-1 block text-xs font-semibold">Apertura</label>
        <input type="time" name="apertura"
               value="{{ old('apertura', $h && $h->apertura ? substr($h->apertura, 0, 5) : '') }}"
               class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-neutral-900">
    </div>
    <div>
        <label class="mb-1 block text-xs font-semibold">Cierre</label>
        <input type="time" name="cierre"
               value="{{ old('cierre', $h && $h->cierre ? substr($h->cierre, 0, 5) : '') }}"
               class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-neutral-900">
    </div>
</div>

<label class="flex items-center gap-2 text-sm">
    <input type="hidden" name="activo" value="0">
    <input type="checkbox" name="activo" value="1" @checked(old('activo', $h->activo ?? true))>
    Activo (si lo desmarcás, el local figura cerrado ese día)
</label>

<div class="flex gap-3">
    <button type="submit" class="rounded-lg bg-black px-5 py-2.5 text-sm font-semibold text-white">Guardar</button>
    <a href="{{ route('admin.horarios.index') }}" class="rounded-lg border border-neutral-300 px-5 py-2.5 text-sm">Cancelar</a>
</div>