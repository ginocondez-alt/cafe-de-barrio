<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    private const ROLES = ['cliente', 'caja', 'cocina', 'administrador'];

    private function reglas(?User $usuario = null): array
    {
        return [
            'name'      => 'required|max:100',
            'apellido'  => 'required|max:100',
            'email'     => ['required', 'email', 'max:255',
                            Rule::unique('users', 'email')->ignore($usuario?->id)],
            'telefono'  => 'required|max:30',
            'direccion' => 'required|max:255',
            'rol'       => ['required', Rule::in(self::ROLES)],
            'password'  => [$usuario ? 'nullable' : 'required', 'min:8', 'confirmed'],
        ];
    }

    public function index(Request $request)
    {
        $usuarios = User::query()
            ->when($request->buscar, function ($q, $b) {
                $q->where(fn ($q) => $q->where('name', 'like', "%{$b}%")
                                       ->orWhere('apellido', 'like', "%{$b}%")
                                       ->orWhere('email', 'like', "%{$b}%"));
            })
            ->when($request->rol, fn ($q, $r) => $q->where('rol', $r))
            ->orderBy('name')
            ->get();

        return view('admin.usuarios.index', [
            'usuarios' => $usuarios,
            'roles'    => self::ROLES,
        ]);
    }

    public function create()
    {
        return view('admin.usuarios.create', ['roles' => self::ROLES]);
    }

    public function store(Request $request)
    {
        $datos = $request->validate($this->reglas());
        $datos['password'] = Hash::make($datos['password']);

        User::forceCreate($datos);

        return redirect()->route('admin.usuarios.index')
            ->with('exito', 'Usuario creado.');
    }

    public function edit(User $usuario)
    {
        return view('admin.usuarios.edit', [
            'usuario' => $usuario,
            'roles'   => self::ROLES,
        ]);
    }

    public function update(Request $request, User $usuario)
    {
        $datos = $request->validate($this->reglas($usuario));

        if ($request->filled('password')) {
            $datos['password'] = Hash::make($datos['password']);
        } else {
            unset($datos['password']);
        }

        // El admin no puede quitarse su propio rol.
        if ($usuario->id === $request->user()->id) {
            $datos['rol'] = $usuario->rol;
        }

        $usuario->forceFill($datos)->save();

        return redirect()->route('admin.usuarios.index')
            ->with('exito', 'Usuario actualizado.');
    }

    public function destroy(Request $request, User $usuario)
    {
        if ($usuario->id === $request->user()->id) {
            return back()->withErrors(['usuario' => 'No podés eliminar tu propio usuario.']);
        }

        $usuario->delete();

        return redirect()->route('admin.usuarios.index')
            ->with('exito', 'Usuario eliminado.');
    }
}
