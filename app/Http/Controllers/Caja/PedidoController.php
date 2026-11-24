<?php

namespace App\Http\Controllers\Caja;

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
            ->whereIn('estado', [Pedido::CONFIRMADO, Pedido::PREPARANDO, Pedido::EN_CAJA])
            ->orderByRaw(
                'CASE WHEN estado = ? THEN 0 WHEN estado = ? THEN 1 WHEN estado = ? THEN 2 ELSE 3 END',
                [Pedido::EN_CAJA, Pedido::CONFIRMADO, Pedido::PREPARANDO]
            )
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();

        return view('caja.pedidos.index', [
            'pedidos' => $pedidos,
        ]);
    }

    public function actualizarEstado(Request $request, Pedido $pedido): RedirectResponse
    {
        $datos = $request->validate([
            'estado' => ['required', Rule::in([Pedido::PREPARANDO, Pedido::RETIRADO])],
        ]);

        if (! in_array($pedido->estado, [Pedido::CONFIRMADO, Pedido::PREPARANDO, Pedido::EN_CAJA], true)) {
            return back()->withErrors(['pedido' => 'Este pedido no se puede modificar desde caja.']);
        }

        if ($datos['estado'] === Pedido::PREPARANDO && $pedido->estado !== Pedido::CONFIRMADO) {
            return back()->withErrors(['pedido' => 'Solo los pedidos confirmados se pueden enviar a preparación.']);
        }

        if ($datos['estado'] === Pedido::RETIRADO && $pedido->estado !== Pedido::EN_CAJA) {
            return back()->withErrors(['pedido' => 'Solo los pedidos recibidos de cocina se pueden entregar.']);
        }

        $pedido->update(['estado' => $datos['estado']]);

        $mensaje = $datos['estado'] === Pedido::RETIRADO
            ? 'Pedido entregado al cliente.'
            : 'Estado del pedido actualizado.';

        return back()->with('exito', $mensaje);
    }
}
