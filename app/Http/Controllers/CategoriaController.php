<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoriaController extends Controller
{
    public function index()
    {
        return view('admin.categorias.index', [
            'categorias' => Categoria::withCount('productos')->orderBy('nombre')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.categorias.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|max:100|unique:categorias,nombre',
        ]);

        Categoria::create($datos);

        return redirect()->route('admin.categorias.index')
            ->with('exito', 'Categoría creada.');
    }

    public function edit(Categoria $categoria)
    {
        return view('admin.categorias.edit', ['categoria' => $categoria]);
    }

    public function update(Request $request, Categoria $categoria)
    {
        $datos = $request->validate([
            'nombre' => [
                'required', 'max:100',
                Rule::unique('categorias', 'nombre')->ignore($categoria->id),
            ],
        ]);

        $categoria->update($datos);

        return redirect()->route('admin.categorias.index')
            ->with('exito', 'Categoría actualizada.');
    }

    public function destroy(Categoria $categoria)
    {
        if ($categoria->productos()->exists()) {
            return back()->withErrors([
                'categoria' => 'No se puede eliminar una categoría que tiene productos.',
            ]);
        }

        $categoria->delete();

        return redirect()->route('admin.categorias.index')
            ->with('exito', 'Categoría eliminada.');
    }
}