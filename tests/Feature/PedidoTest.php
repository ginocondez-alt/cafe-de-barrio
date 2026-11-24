<?php

use App\Models\Categoria;
use App\Models\ItemPedido;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

/**
 * @return array{User, Producto}
 */
function clienteConProducto(): array
{
    $cliente = User::factory()->create([
        'apellido' => 'Café',
        'telefono' => '0000000000',
        'direccion' => 'Barrio',
    ]);

    $categoria = Categoria::create(['nombre' => 'Cafés']);
    $producto = Producto::create([
        'categoria_id' => $categoria->id,
        'nombre' => 'Café con leche',
        'descripcion' => 'Espresso doble con leche vaporizada',
        'precio' => 3200,
    ]);

    return [$cliente, $producto];
}

function crearOtroCliente(): User
{
    return User::factory()->create([
        'apellido' => 'Otro',
        'telefono' => '1111111111',
        'direccion' => 'Otro barrio',
    ]);
}

it('oculta el botón agregar a mi pedido para los visitantes', function () {
    [, $producto] = clienteConProducto();

    $this->get(route('menu.index'))
        ->assertOk()
        ->assertDontSee('Agregar a mi pedido');
});

it('muestra el botón agregar a mi pedido para los usuarios autenticados', function () {
    [$cliente, $producto] = clienteConProducto();

    $this->actingAs($cliente)->get(route('menu.index'))
        ->assertOk()
        ->assertSee('Agregar a mi pedido')
        ->assertSee(route('pedidos.agregar', $producto), false);
});

it('redirige a los visitantes al acceso al abrir mis pedidos', function () {
    $this->get(route('pedidos.index'))
        ->assertRedirect(route('login'));
});

it('redirige a los visitantes al acceso al intentar agregar un producto', function () {
    [, $producto] = clienteConProducto();

    $this->post(route('pedidos.agregar', $producto))
        ->assertRedirect(route('login'));
});

it('agrega un producto al pedido pendiente del usuario', function () {
    [$cliente, $producto] = clienteConProducto();

    $respuesta = $this->actingAs($cliente)
        ->from(route('menu.index'))
        ->post(route('pedidos.agregar', $producto));

    $pedido = $cliente->pedidos()->firstOrFail();

    $respuesta->assertRedirect(route('menu.index'));
    $respuesta->assertSessionHas('exito', 'Producto agregado a tu pedido.');
    expect($pedido->estado)->toBe(Pedido::PENDIENTE);
    $this->assertDatabaseHas('item_pedidos', [
        'pedido_id' => $pedido->id,
        'producto_id' => $producto->id,
        'nombre' => 'Café con leche',
        'precio_unitario' => 3200,
        'cantidad' => 1,
    ]);
});

it('suma la cantidad al agregar dos veces el mismo producto', function () {
    [$cliente, $producto] = clienteConProducto();

    $this->actingAs($cliente)->from(route('menu.index'))->post(route('pedidos.agregar', $producto));
    $this->actingAs($cliente)->from(route('menu.index'))->post(route('pedidos.agregar', $producto));

    $pedido = $cliente->pedidos()->firstOrFail();

    $this->assertDatabaseCount('pedidos', 1);
    $this->assertDatabaseCount('item_pedidos', 1);
    $this->assertDatabaseHas('item_pedidos', [
        'pedido_id' => $pedido->id,
        'producto_id' => $producto->id,
        'cantidad' => 2,
    ]);
});

it('muestra en mis pedidos el producto, el estado y el total', function () {
    [$cliente, $producto] = clienteConProducto();

    $this->actingAs($cliente)
        ->from(route('menu.index'))
        ->post(route('pedidos.agregar', $producto));

    $pedido = $cliente->pedidos()->firstOrFail();

    $this->actingAs($cliente)->get(route('pedidos.index'))
        ->assertOk()
        ->assertSee('Mis pedidos')
        ->assertSee('Pedido #'.$pedido->id)
        ->assertSee('Café con leche')
        ->assertSee('Pendiente')
        ->assertSee('Confirmar pedido')
        ->assertSee('$3.200', false);
});

it('muestra un estado vacío cuando el usuario no tiene pedidos', function () {
    [$cliente] = clienteConProducto();

    $this->actingAs($cliente)->get(route('pedidos.index'))
        ->assertOk()
        ->assertSee('Todavía no hiciste ningún pedido.');
});

it('quita un producto y elimina el pedido cuando queda vacío', function () {
    [$cliente, $producto] = clienteConProducto();

    $this->actingAs($cliente)
        ->from(route('menu.index'))
        ->post(route('pedidos.agregar', $producto));

    $item = ItemPedido::firstOrFail();

    $respuesta = $this->actingAs($cliente)
        ->from(route('pedidos.index'))
        ->delete(route('pedidos.quitar', $item));

    $respuesta->assertRedirect(route('pedidos.index'));
    $respuesta->assertSessionHas('exito', 'Producto quitado de tu pedido.');
    $this->assertDatabaseCount('item_pedidos', 0);
    $this->assertDatabaseCount('pedidos', 0);
});

it('confirma el pedido y usa uno nuevo para el siguiente producto', function () {
    [$cliente, $producto] = clienteConProducto();
    $otroProducto = Producto::create([
        'categoria_id' => $producto->categoria_id,
        'nombre' => 'Medialuna',
        'descripcion' => 'Recién horneada',
        'precio' => 2500,
    ]);

    $this->actingAs($cliente)
        ->from(route('menu.index'))
        ->post(route('pedidos.agregar', $producto));

    $pedido = $cliente->pedidos()->firstOrFail();

    $this->actingAs($cliente)
        ->from(route('pedidos.index'))
        ->patch(route('pedidos.confirmar', $pedido))
        ->assertRedirect(route('pedidos.index'));

    $this->actingAs($cliente)
        ->from(route('menu.index'))
        ->post(route('pedidos.agregar', $otroProducto));

    $this->assertDatabaseCount('pedidos', 2);
    expect($pedido->refresh()->estado)->toBe(Pedido::CONFIRMADO);
    expect($cliente->pedidos()->where('estado', Pedido::PENDIENTE)->firstOrFail()->items)->toHaveCount(1);
});

it('no permite quitar items del pedido de otro usuario', function () {
    [$cliente, $producto] = clienteConProducto();
    $otroCliente = crearOtroCliente();
    $pedido = Pedido::factory()->create(['user_id' => $cliente->id]);
    $item = ItemPedido::factory()->paraProducto($producto)->create(['pedido_id' => $pedido->id]);

    $this->actingAs($otroCliente)
        ->from(route('pedidos.index'))
        ->delete(route('pedidos.quitar', $item))
        ->assertForbidden();

    $this->assertModelExists($item);
});

it('no permite confirmar el pedido de otro usuario', function () {
    [$cliente, $producto] = clienteConProducto();
    $otroCliente = crearOtroCliente();
    $pedido = Pedido::factory()->create(['user_id' => $cliente->id]);
    ItemPedido::factory()->paraProducto($producto)->create(['pedido_id' => $pedido->id]);

    $this->actingAs($otroCliente)
        ->from(route('pedidos.index'))
        ->patch(route('pedidos.confirmar', $pedido))
        ->assertForbidden();

    expect($pedido->refresh()->estado)->toBe(Pedido::PENDIENTE);
});

it('no permite quitar productos de un pedido confirmado', function () {
    [$cliente, $producto] = clienteConProducto();
    $pedido = Pedido::factory()->confirmado()->create(['user_id' => $cliente->id]);
    $item = ItemPedido::factory()->paraProducto($producto)->create(['pedido_id' => $pedido->id]);

    $this->actingAs($cliente)
        ->from(route('pedidos.index'))
        ->delete(route('pedidos.quitar', $item))
        ->assertForbidden();

    $this->assertModelExists($item);
});

it('conserva el historial cuando se elimina el producto', function () {
    [$cliente, $producto] = clienteConProducto();

    $this->actingAs($cliente)
        ->from(route('menu.index'))
        ->post(route('pedidos.agregar', $producto));

    $producto->delete();

    $this->actingAs($cliente)->get(route('pedidos.index'))
        ->assertOk()
        ->assertSee('Café con leche')
        ->assertSee('$3.200', false);

    $this->assertDatabaseHas('item_pedidos', [
        'nombre' => 'Café con leche',
        'precio_unitario' => 3200,
        'producto_id' => null,
    ]);
});

it('muestra el botón borrar solo en pedidos cerrados', function () {
    [$cliente] = clienteConProducto();
    $entregado = Pedido::factory()->entregado()->create(['user_id' => $cliente->id]);
    $confirmado = Pedido::factory()->confirmado()->create(['user_id' => $cliente->id]);

    $this->actingAs($cliente)->get(route('pedidos.index'))
        ->assertOk()
        ->assertSee(route('pedidos.destroy', $entregado), false)
        ->assertDontSee(route('pedidos.destroy', $confirmado), false);
});

it('redirige a los visitantes al intentar borrar un pedido', function () {
    $cliente = crearOtroCliente();
    $pedido = Pedido::factory()->entregado()->create(['user_id' => $cliente->id]);

    $this->delete(route('pedidos.destroy', $pedido))
        ->assertRedirect(route('login'));

    $this->assertModelExists($pedido);
});

it('borra un pedido cerrado y sus items', function () {
    [$cliente, $producto] = clienteConProducto();
    $pedido = Pedido::factory()->entregado()->create(['user_id' => $cliente->id]);
    ItemPedido::factory()->paraProducto($producto)->create(['pedido_id' => $pedido->id]);

    $this->actingAs($cliente)
        ->from(route('pedidos.index'))
        ->delete(route('pedidos.destroy', $pedido))
        ->assertRedirect(route('pedidos.index'))
        ->assertSessionHas('exito', 'Pedido borrado de tu historial.');

    $this->assertModelMissing($pedido);
    $this->assertDatabaseCount('item_pedidos', 0);
});

it('no permite borrar un pedido confirmado', function () {
    [$cliente, $producto] = clienteConProducto();
    $pedido = Pedido::factory()->confirmado()->create(['user_id' => $cliente->id]);
    ItemPedido::factory()->paraProducto($producto)->create(['pedido_id' => $pedido->id]);

    $this->actingAs($cliente)
        ->from(route('pedidos.index'))
        ->delete(route('pedidos.destroy', $pedido))
        ->assertRedirect(route('pedidos.index'))
        ->assertSessionHasErrors('pedido');

    $this->assertModelExists($pedido);
    $this->assertDatabaseCount('item_pedidos', 1);
});

it('no permite borrar el pedido de otro usuario', function () {
    [$cliente, $producto] = clienteConProducto();
    $otroCliente = crearOtroCliente();
    $pedido = Pedido::factory()->entregado()->create(['user_id' => $otroCliente->id]);
    ItemPedido::factory()->paraProducto($producto)->create(['pedido_id' => $pedido->id]);

    $this->actingAs($cliente)
        ->from(route('pedidos.index'))
        ->delete(route('pedidos.destroy', $pedido))
        ->assertForbidden();

    $this->assertModelExists($pedido);
});
