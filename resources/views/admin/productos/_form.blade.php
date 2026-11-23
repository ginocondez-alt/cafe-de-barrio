<div>
    <label class="mb-1 block text-xs font-semibold">Nombre</label>
    <input type="text" name="nombre" value="{{ old('nombre', $producto->nombre ?? '') }}"
           class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-neutral-900">
</div>

<div>
    <label class="mb-1 block text-xs font-semibold">Categoría</label>
    <select name="categoria_id"
            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-neutral-900">
        <option value="">Elegí una categoría</option>
        @foreach ($categorias as $categoria)
            <option value="{{ $categoria->id }}"
                @selected(old('categoria_id', $producto->categoria_id ?? '') == $categoria->id)>
                {{ $categoria->nombre }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <label class="mb-1 block text-xs font-semibold">Precio</label>
    <input type="number" name="precio" step="0.01" min="0"
           value="{{ old('precio', $producto->precio ?? '') }}"
           class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-neutral-900">
</div>

<div>
    <label class="mb-1 block text-xs font-semibold">Descripción</label>
    <textarea name="descripcion" rows="3"
              class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-neutral-900">{{ old('descripcion', $producto->descripcion ?? '') }}</textarea>
</div>

<div>
    <label for="imagen" class="mb-1 block text-xs font-semibold">Imagen</label>

    @if (!empty($producto->imagen))
        <div class="mb-2 flex items-center gap-3">
            <img src="{{ Storage::url($producto->imagen) }}"
                 alt="{{ $producto->nombre ?? 'Imagen del producto' }}"
                 class="h-16 w-16 rounded-lg border border-neutral-200 object-cover">
            <span class="text-xs text-neutral-500">Imagen actual (subí otra solo si querés reemplazarla)</span>
        </div>
    @endif

    <input type="file" id="imagen" name="imagen" accept="image/jpeg"
           class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-neutral-900 file:mr-3 file:rounded-md file:border-0 file:bg-neutral-900 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white hover:file:bg-neutral-800">

    @error('imagen')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="flex gap-3">
    <button type="submit" class="rounded-lg bg-black px-5 py-2.5 text-sm font-semibold text-white">Guardar</button>
    <a href="{{ route('admin.productos.index') }}" class="rounded-lg border border-neutral-300 px-5 py-2.5 text-sm">Cancelar</a>
</div>
