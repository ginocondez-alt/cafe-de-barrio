<?php

namespace App\Http\Controllers\Cocina;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PedidoController extends Controller
{
    public function index(): View
    {
        $pedidos = Pedido::query()
            ->with(['items', 'user'])
            ->whereIn('estado', [Pedido::CONFIRMADO, Pedido::PREPARANDO])
            ->orderByRaw('CASE WHEN estado = ? THEN 0 ELSE 1 END', [Pedido::CONFIRMADO])
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();

        return view('cocina.pedidos.index', [
            'pedidos' => $pedidos,
        ]);
    }

    public function actualizarEstado(Request $request, Pedido $pedido): RedirectResponse
    {
        $datos = $request->validate([
            'estado' => ['required', Rule::in([Pedido::PREPARANDO, Pedido::EN_CAJA, Pedido::CANCELADO])],
        ]);

        if (! in_array($pedido->estado, [Pedido::CONFIRMADO, Pedido::PREPARANDO], true)) {
            return back()->withErrors(['pedido' => 'Este pedido no se puede modificar desde cocina.']);
        }

        $pedido->update(['estado' => $datos['estado']]);

        $mensaje = $datos['estado'] === Pedido::EN_CAJA
            ? 'Pedido enviado a caja.'
            : 'Estado del pedido actualizado.';

        return back()->with('exito', $mensaje);
    }
}
