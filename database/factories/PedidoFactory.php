<?php

namespace Database\Factories;

use App\Models\Pedido;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pedido>
 */
class PedidoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'estado' => Pedido::PENDIENTE,
        ];
    }

    /**
     * Indicate that the order was already confirmed.
     */
    public function confirmado(): static
    {
        return $this->state(fn (array $attributes): array => [
            'estado' => Pedido::CONFIRMADO,
        ]);
    }

    /**
     * Indicate that the order is being prepared.
     */
    public function preparando(): static
    {
        return $this->state(fn (array $attributes): array => [
            'estado' => Pedido::PREPARANDO,
        ]);
    }

    /**
     * Indicate that the order was finalized.
     */
    public function finalizado(): static
    {
        return $this->state(fn (array $attributes): array => [
            'estado' => Pedido::FINALIZADO,
        ]);
    }

    /**
     * Indicate that the order was cancelled.
     */
    public function cancelado(): static
    {
        return $this->state(fn (array $attributes): array => [
            'estado' => Pedido::CANCELADO,
        ]);
    }
}
