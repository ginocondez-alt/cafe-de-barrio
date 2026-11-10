@if ($errors->any())
    <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800" role="alert">
        <ul class="list-inside list-disc">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif