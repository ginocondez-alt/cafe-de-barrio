<?php

use App\Models\ItemPedido;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

function usuarioCocina(): User
{
    return User::factory()->create([
        'apellido' => 'Cocina',
        'telefono' => '0000000000',
        'direccion' => 'Local',
        'rol' => 'cocina',
    ]);
}

function usuarioCliente(): User
{
    return User::factory()->create([
        'apellido' => 'Cliente',
        'telefono' => '1111111111',
        'direccion' => 'Barrio',
        'rol' => 'cliente',
    ]);
}

function pedidoConItem(string $estado = Pedido::CONFIRMADO): Pedido
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

it('redirige a los visitantes al acceso al abrir cocina', function () {
    $this->get(route('cocina.pedidos.index'))
        ->assertRedirect(route('login'));
});

it('impide a un cliente entrar a cocina', function () {
    $cliente = usuarioCliente();

    $this->actingAs($cliente)
        ->get(route('cocina.pedidos.index'))
        ->assertForbidden();
});

it('muestra pedidos confirmados y en preparación en cocina', function () {
    $cocina = usuarioCocina();
    $confirmado = pedidoConItem(Pedido::CONFIRMADO);
    $preparando = pedidoConItem(Pedido::PREPARANDO);
    $finalizado = pedidoConItem(Pedido::FINALIZADO);

    $this->actingAs($cocina)
        ->get(route('cocina.pedidos.index'))
        ->assertOk()
        ->assertSee('Pedido #'.$confirmado->id)
        ->assertSee('Pedido #'.$preparando->id)
        ->assertDontSee('Pedido #'.$finalizado->id);
});

it('permite a cocina marcar un pedido confirmado como preparando', function () {
    $cocina = usuarioCocina();
    $pedido = pedidoConItem(Pedido::CONFIRMADO);

    $this->actingAs($cocina)
        ->from(route('cocina.pedidos.index'))
        ->patch(route('cocina.pedidos.estado', $pedido), ['estado' => Pedido::PREPARANDO])
        ->assertRedirect(route('cocina.pedidos.index'))
        ->assertSessionHas('exito');

    expect($pedido->refresh()->estado)->toBe(Pedido::PREPARANDO);
});

it('permite a cocina cancelar un pedido confirmado', function () {
    $cocina = usuarioCocina();
    $pedido = pedidoConItem(Pedido::CONFIRMADO);

    $this->actingAs($cocina)
        ->from(route('cocina.pedidos.index'))
        ->patch(route('cocina.pedidos.estado', $pedido), ['estado' => Pedido::CANCELADO])
        ->assertRedirect(route('cocina.pedidos.index'));

    expect($pedido->refresh()->estado)->toBe(Pedido::CANCELADO);
});

it('permite a cocina marcar un pedido como listo para retirar', function () {
    $cocina = usuarioCocina();
    $pedido = pedidoConItem(Pedido::CONFIRMADO);

    $this->actingAs($cocina)
        ->from(route('cocina.pedidos.index'))
        ->patch(route('cocina.pedidos.estado', $pedido), ['estado' => Pedido::FINALIZADO])
        ->assertRedirect(route('cocina.pedidos.index'))
        ->assertSessionHas('exito');

    expect($pedido->refresh()->estado)->toBe(Pedido::FINALIZADO);
});

it('no permite a cocina modificar un pedido ya finalizado', function () {
    $cocina = usuarioCocina();
    $pedido = pedidoConItem(Pedido::FINALIZADO);

    $this->actingAs($cocina)
        ->from(route('cocina.pedidos.index'))
        ->patch(route('cocina.pedidos.estado', $pedido), ['estado' => Pedido::CANCELADO])
        ->assertRedirect(route('cocina.pedidos.index'))
        ->assertSessionHasErrors('pedido');

    expect($pedido->refresh()->estado)->toBe(Pedido::FINALIZADO);
});
