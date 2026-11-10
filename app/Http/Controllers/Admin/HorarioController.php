<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Horario;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    private function validar(Request $request): array
    {
        $datos = $request->validate([
            'dia'      => 'required|integer|between:1,7',
            'activo'   => 'required|boolean',
            'apertura' => 'nullable|required_if:activo,1|date_format:H:i',
            'cierre'   => 'nullable|required_if:activo,1|date_format:H:i|after:apertura',
        ]);

        // Un día inactivo no guarda horas.
        if (! $datos['activo']) {
            $datos['apertura'] = null;
            $datos['cierre']   = null;
        }

        return $datos;
    }

    public function index()
    {
        return view('admin.horarios.index', [
            'horarios' => Horario::orderBy('dia')->orderBy('apertura')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.horarios.create');
    }

    public function store(Request $request)
    {
        Horario::create($this->validar($request));

        return redirect()->route('admin.horarios.index')
            ->with('exito', 'Horario creado.');
    }

    public function edit(Horario $horario)
    {
        return view('admin.horarios.edit', ['horario' => $horario]);
    }

    public function update(Request $request, Horario $horario)
    {
        $horario->update($this->validar($request));

        return redirect()->route('admin.horarios.index')
            ->with('exito', 'Horario actualizado.');
    }

    public function destroy(Horario $horario)
    {
        $horario->delete();

        return redirect()->route('admin.horarios.index')
            ->with('exito', 'Horario eliminado.');
    }
}