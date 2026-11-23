<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductoController extends Controller
{
    private function reglas(): array
    {
        return [
            'categoria_id' => 'required|exists:categorias,id',
            'nombre' => 'required|max:100',
            'descripcion' => 'required|max:500',
            'precio' => 'required|numeric|min:0|max:9999999',
            'imagen' => 'nullable|image|mimes:jpeg|extensions:jpeg|max:2048',
        ];
    }

    /**
     * Guarda la imagen en storage/app/public/productos y actualiza la ruta del producto.
     */
    private function guardarImagen(Request $request, Producto $producto): void
    {
        if (! $request->hasFile('imagen')) {
            return;
        }

        $imagenAnterior = $producto->imagen;
        $producto->imagen = $request->file('imagen')->storeAs(
            'productos',
            Str::random(40).'.jpeg',
            'public'
        );
        $producto->save();

        if ($imagenAnterior) {
            Storage::disk('public')->delete($imagenAnterior);
        }
    }

    public function index(Request $request)
    {
        $productos = Producto::with('categoria')
            ->when($request->buscar, fn ($q, $b) => $q->where('nombre', 'like', "%{$b}%"))
            ->when($request->categoria, fn ($q, $c) => $q->where('categoria_id', $c))
            ->orderBy('nombre')
            ->get();

        return view('admin.productos.index', [
            'productos' => $productos,
            'categorias' => Categoria::orderBy('nombre')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.productos.create', [
            'categorias' => Categoria::orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $datos = $request->validate($this->reglas());

        $producto = Producto::create(Arr::except($datos, ['imagen']));
        $this->guardarImagen($request, $producto);

        return redirect()->route('admin.productos.index')
            ->with('exito', 'Producto creado.');
    }

    public function edit(Producto $producto)
    {
        return view('admin.productos.edit', [
            'producto' => $producto,
            'categorias' => Categoria::orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request, Producto $producto)
    {
        $datos = $request->validate($this->reglas());

        $producto->update(Arr::except($datos, ['imagen']));
        $this->guardarImagen($request, $producto);

        return redirect()->route('admin.productos.index')
            ->with('exito', 'Producto actualizado.');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();

        return redirect()->route('admin.productos.index')
            ->with('exito', 'Producto eliminado.');
    }
}
