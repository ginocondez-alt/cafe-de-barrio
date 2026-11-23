<?php

namespace Database\Factories;

use App\Models\ItemPedido;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ItemPedido>
 */
class ItemPedidoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $producto = Producto::factory()->create();

        return [
            'pedido_id' => Pedido::factory(),
            'producto_id' => $producto->id,
            'nombre' => $producto->nombre,
            'imagen' => $producto->imagen,
            'precio_unitario' => $producto->precio,
            'cantidad' => fake()->numberBetween(1, 3),
        ];
    }

    /**
     * Use the given product as the item's source.
     */
    public function paraProducto(Producto $producto): static
    {
        return $this->state(fn (array $attributes): array => [
            'producto_id' => $producto->id,
            'nombre' => $producto->nombre,
            'imagen' => $producto->imagen,
            'precio_unitario' => $producto->precio,
        ]);
    }
}
