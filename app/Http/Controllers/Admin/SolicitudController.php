<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Solicitud;

class SolicitudController extends Controller
{
     public function index(){
        $solicitudes = Solicitud::orderBy('created_at', 'desc')->get();
        return view('admin.solicitudes.index', compact('solicitudes'));
    }
}
