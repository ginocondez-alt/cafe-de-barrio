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
            ->whereIn('estado', [Pedido::CONFIRMADO, Pedido::PREPARANDO, Pedido::FINALIZADO])
            ->orderByRaw(
                'CASE WHEN estado = ? THEN 0 WHEN estado = ? THEN 1 ELSE 2 END',
                [Pedido::CONFIRMADO, Pedido::PREPARANDO]
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
            'estado' => ['required', Rule::in([Pedido::PREPARANDO, Pedido::FINALIZADO, Pedido::RETIRADO])],
        ]);

        if (! in_array($pedido->estado, [Pedido::CONFIRMADO, Pedido::PREPARANDO, Pedido::FINALIZADO], true)) {
            return back()->withErrors(['pedido' => 'Este pedido no se puede modificar desde caja.']);
        }

        if ($datos['estado'] === Pedido::RETIRADO && $pedido->estado !== Pedido::FINALIZADO) {
            return back()->withErrors(['pedido' => 'Solo los pedidos listos para retirar se pueden quitar de la lista.']);
        }

        $pedido->update(['estado' => $datos['estado']]);

        return back()->with('exito', 'Estado del pedido actualizado.');
    }
}
