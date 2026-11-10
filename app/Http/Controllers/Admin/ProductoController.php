<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    private function reglas(): array
    {
        return [
            'categoria_id' => 'required|exists:categorias,id',
            'nombre'       => 'required|max:100',
            'descripcion'  => 'required|max:500',
            'precio'       => 'required|numeric|min:0|max:9999999',
        ];
    }

    public function index(Request $request)
    {
        $productos = Producto::with('categoria')
            ->when($request->buscar, fn ($q, $b) => $q->where('nombre', 'like', "%{$b}%"))
            ->when($request->categoria, fn ($q, $c) => $q->where('categoria_id', $c))
            ->orderBy('nombre')
            ->get();

        return view('admin.productos.index', [
            'productos'  => $productos,
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
        Producto::create($request->validate($this->reglas()));

        return redirect()->route('admin.productos.index')
            ->with('exito', 'Producto creado.');
    }

    public function edit(Producto $producto)
    {
        return view('admin.productos.edit', [
            'producto'   => $producto,
            'categorias' => Categoria::orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request, Producto $producto)
    {
        $producto->update($request->validate($this->reglas()));

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