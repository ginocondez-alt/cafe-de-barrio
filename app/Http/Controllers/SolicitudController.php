<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitud;

class SolicitudController extends Controller
{
    public function store(Request $request){
       $datos = $request->validate([
             'tipo' => 'required|in:queja,sugerencia,pedido_especial,consulta',
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'asunto' => 'required|string|max:255',
            'mensaje' => 'required|string',
        ]);
         Solicitud::create($datos);
        return redirect()->back()->with(
            'success', '¡Gracias por escribirnos! Recibimos su solicitud correctamente'
        );
    }
}
