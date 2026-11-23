<?php

namespace Database\Seeders;

use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cliente = User::firstOrCreate(
            ['email' => 'cliente@cafe.test'],
            [
                'name' => 'Cliente',
                'apellido' => 'Café',
                'telefono' => '0000000000',
                'direccion' => 'Local',
                'rol' => 'cliente',
                'password' => Hash::make('12345678'),
            ]
        );

        $pedido = Pedido::firstOrCreate([
            'user_id' => $cliente->id,
            'estado' => Pedido::CONFIRMADO,
        ]);

        $producto = Producto::where('nombre', 'Café con leche')->first();

        if ($producto && $pedido->items()->doesntExist()) {
            $pedido->items()->create([
                'producto_id' => $producto->id,
                'nombre' => $producto->nombre,
                'imagen' => $producto->imagen,
                'precio_unitario' => $producto->precio,
                'cantidad' => 2,
            ]);
        }
    }
}
