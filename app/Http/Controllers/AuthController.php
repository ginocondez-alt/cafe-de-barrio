<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function mostrarRegistro()
    {
        return view('auth.registro');
    }

    public function registrar(Request $request)
    {
        $datos = $request->validate([
            'name'      => 'required|max:100',
            'apellido'  => 'required|max:100',
            'email'     => 'required|email|max:255|unique:users,email',
            'telefono'  => 'required|max:30',
            'direccion' => 'required|max:255',
            'password'  => 'required|min:8|confirmed',
        ]);

        $datos['password'] = Hash::make($datos['password']);

        $user = User::create($datos);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('menu.index');
    }

    public function mostrarLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credenciales)) {
        $request->session()->regenerate();
        return redirect()->intended($request->user()->rutaInicio());
        }

        return back()
            ->withErrors(['email' => 'Email o contraseña incorrectos.'])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('menu.index');
    }
}