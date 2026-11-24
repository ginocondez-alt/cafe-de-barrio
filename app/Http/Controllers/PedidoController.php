<?php

namespace App\Http\Controllers;

use App\Models\ItemPedido;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PedidoController extends Controller
{
    public function index(Request $request): View
    {
        $pedidos = $request->user()->pedidos()
            ->with('items')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        return view('pedidos.index', [
            'pedidos' => $pedidos,
        ]);
    }

    public function agregar(Request $request, Producto $producto): RedirectResponse
    {
        $pedido = $this->pedidoActual($request);
        $item = $pedido->items()->firstOrNew(['producto_id' => $producto->id]);

        if ($item->exists) {
            $item->cantidad += 1;
        } else {
            $item->fill([
                'nombre' => $producto->nombre,
                'imagen' => $producto->imagen,
                'precio_unitario' => $producto->precio,
                'cantidad' => 1,
            ]);
        }

        $item->save();

        return back()->with('exito', 'Producto agregado a tu pedido.');
    }

    public function quitar(Request $request, ItemPedido $item): RedirectResponse
    {
        $pedido = $item->pedido;
        $this->verificarPropietario($request, $pedido);

        if (! $pedido->esPendiente()) {
            abort(403, 'No podés modificar un pedido ya confirmado.');
        }

        $item->delete();

        if ($pedido->items()->doesntExist()) {
            $pedido->delete();
        }

        return back()->with('exito', 'Producto quitado de tu pedido.');
    }

    public function confirmar(Request $request, Pedido $pedido): RedirectResponse
    {
        $this->verificarPropietario($request, $pedido);

        if (! $pedido->esPendiente()) {
            return back()->withErrors(['pedido' => 'Este pedido ya está confirmado.']);
        }

        if ($pedido->items()->doesntExist()) {
            return back()->withErrors(['pedido' => 'No hay productos para confirmar.']);
        }

        $pedido->update(['estado' => Pedido::CONFIRMADO]);

        return back()->with('exito', 'Pedido confirmado.');
    }

    public function destroy(Request $request, Pedido $pedido): RedirectResponse
    {
        $this->verificarPropietario($request, $pedido);

        if (! $pedido->esCerrado()) {
            return back()->withErrors(['pedido' => 'Solo podés borrar pedidos cerrados.']);
        }

        $pedido->delete();

        return back()->with('exito', 'Pedido borrado de tu historial.');
    }

    private function pedidoActual(Request $request): Pedido
    {
        return $request->user()->pedidos()->firstOrCreate([
            'estado' => Pedido::PENDIENTE,
        ]);
    }

    private function verificarPropietario(Request $request, Pedido $pedido): void
    {
        if ($pedido->user_id !== $request->user()->id) {
            abort(403, 'Este pedido no es tuyo.');
        }
    }
}
