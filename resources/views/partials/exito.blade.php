@if (session('exito'))
    <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800" role="status">
        {{ session('exito') }}
    </div>
@endif
