<?php

use App\Models\ItemPedido;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

function usuarioCaja(): User
{
    return User::factory()->create([
        'apellido' => 'Caja',
        'telefono' => '0000000000',
        'direccion' => 'Local',
        'rol' => 'caja',
    ]);
}

function clienteCaja(): User
{
    return User::factory()->create([
        'apellido' => 'Cliente',
        'telefono' => '1111111111',
        'direccion' => 'Barrio',
        'rol' => 'cliente',
    ]);
}

function pedidoCajaConItem(string $estado = Pedido::CONFIRMADO): Pedido
{
    $cliente = User::factory()->create([
        'apellido' => 'Cliente',
        'telefono' => '2222222222',
        'direccion' => 'Barrio',
        'rol' => 'cliente',
    ]);

    $pedido = Pedido::factory()->create([
        'user_id' => $cliente->id,
        'estado' => $estado,
    ]);

    $producto = Producto::factory()->create();

    ItemPedido::factory()->paraProducto($producto)->create([
        'pedido_id' => $pedido->id,
    ]);

    return $pedido;
}

it('redirige a los visitantes al acceso al abrir caja', function () {
    $this->get(route('caja.pedidos.index'))
        ->assertRedirect(route('login'));
});

it('impide a un cliente entrar a caja', function () {
    $cliente = clienteCaja();

    $this->actingAs($cliente)
        ->get(route('caja.pedidos.index'))
        ->assertForbidden();
});

it('muestra pedidos confirmados y en preparación en caja', function () {
    $caja = usuarioCaja();
    $confirmado = pedidoCajaConItem(Pedido::CONFIRMADO);
    $preparando = pedidoCajaConItem(Pedido::PREPARANDO);
    $pendiente = pedidoCajaConItem(Pedido::PENDIENTE);

    $this->actingAs($caja)
        ->get(route('caja.pedidos.index'))
        ->assertOk()
        ->assertSee('Pedido #'.$confirmado->id)
        ->assertSee('Pedido #'.$preparando->id)
        ->assertDontSee('Pedido #'.$pendiente->id);
});

it('permite a caja marcar un pedido confirmado como preparando', function () {
    $caja = usuarioCaja();
    $pedido = pedidoCajaConItem(Pedido::CONFIRMADO);

    $this->actingAs($caja)
        ->from(route('caja.pedidos.index'))
        ->patch(route('caja.pedidos.estado', $pedido), ['estado' => Pedido::PREPARANDO])
        ->assertRedirect(route('caja.pedidos.index'))
        ->assertSessionHas('exito');

    expect($pedido->refresh()->estado)->toBe(Pedido::PREPARANDO);
});

it('no permite a caja marcar un pedido como recibido de cocina', function () {
    $caja = usuarioCaja();
    $pedido = pedidoCajaConItem(Pedido::PREPARANDO);

    $this->actingAs($caja)
        ->from(route('caja.pedidos.index'))
        ->patch(route('caja.pedidos.estado', $pedido), ['estado' => Pedido::EN_CAJA])
        ->assertRedirect(route('caja.pedidos.index'))
        ->assertSessionHasErrors('estado');

    expect($pedido->refresh()->estado)->toBe(Pedido::PREPARANDO);
});

it('no permite a caja cancelar un pedido', function () {
    $caja = usuarioCaja();
    $pedido = pedidoCajaConItem(Pedido::CONFIRMADO);

    $this->actingAs($caja)
        ->patch(route('caja.pedidos.estado', $pedido), ['estado' => Pedido::CANCELADO])
        ->assertSessionHasErrors('estado');

    expect($pedido->refresh()->estado)->toBe(Pedido::CONFIRMADO);
});

it('permite a caja acceder al menú y a mis pedidos', function () {
    $caja = usuarioCaja();

    $this->actingAs($caja)->get(route('menu.index'))->assertOk();
    $this->actingAs($caja)->get(route('pedidos.index'))->assertOk();
});

it('muestra pedidos recibidos de cocina en caja', function () {
    $caja = usuarioCaja();
    $enCaja = pedidoCajaConItem(Pedido::EN_CAJA);

    $this->actingAs($caja)
        ->get(route('caja.pedidos.index'))
        ->assertOk()
        ->assertSee('Pedido #'.$enCaja->id)
        ->assertSee('En caja')
        ->assertSee('Entregar al cliente');
});

it('permite a caja entregar al cliente un pedido recibido de cocina', function () {
    $caja = usuarioCaja();
    $pedido = pedidoCajaConItem(Pedido::EN_CAJA);

    $this->actingAs($caja)
        ->from(route('caja.pedidos.index'))
        ->patch(route('caja.pedidos.estado', $pedido), ['estado' => Pedido::RETIRADO])
        ->assertRedirect(route('caja.pedidos.index'))
        ->assertSessionHas('exito');

    expect($pedido->refresh()->estado)->toBe(Pedido::RETIRADO);
});

it('no permite a caja devolver a preparación un pedido que cocina ya envió', function () {
    $caja = usuarioCaja();
    $pedido = pedidoCajaConItem(Pedido::EN_CAJA);

    $this->actingAs($caja)
        ->from(route('caja.pedidos.index'))
        ->patch(route('caja.pedidos.estado', $pedido), ['estado' => Pedido::PREPARANDO])
        ->assertRedirect(route('caja.pedidos.index'))
        ->assertSessionHasErrors('pedido');

    expect($pedido->refresh()->estado)->toBe(Pedido::EN_CAJA);
});

it('no permite a caja entregar un pedido que no está en caja', function () {
    $caja = usuarioCaja();
    $pedido = pedidoCajaConItem(Pedido::CONFIRMADO);

    $this->actingAs($caja)
        ->from(route('caja.pedidos.index'))
        ->patch(route('caja.pedidos.estado', $pedido), ['estado' => Pedido::RETIRADO])
        ->assertRedirect(route('caja.pedidos.index'))
        ->assertSessionHasErrors('pedido');

    expect($pedido->refresh()->estado)->toBe(Pedido::CONFIRMADO);
});
