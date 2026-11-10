<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        
        $personal = [
        ['name' => 'Gino', 'email' => 'admin@cafe.test',  'rol' => 'administrador'],
        ['name' => 'Caja',  'email' => 'caja@cafe.test',   'rol' => 'caja'],
        ['name' => 'Cocina','email' => 'cocina@cafe.test', 'rol' => 'cocina'],
        ];
        foreach ($personal as $dato) {
            User::forceCreate($dato + [
                'apellido'  => 'Café',
                'telefono'  => '0000000000',
                'direccion' => 'Local',
                'password'  => Hash::make('12345678'),
            ]);
        }

        // Categorías
        $cafes     = Categoria::create(['nombre' => 'Cafés']);
        $tostados  = Categoria::create(['nombre' => 'Tostados']);
        $ensaladas = Categoria::create(['nombre' => 'Ensaladas']);
        $postres   = Categoria::create(['nombre' => 'Postres']);

        // Productos
        Producto::create([
            'categoria_id' => $cafes->id,
            'nombre'       => 'Café con leche',
            'descripcion'  => 'Espresso doble con leche vaporizada',
            'precio'       => 3200,
        ]);
        Producto::create([
            'categoria_id' => $tostados->id,
            'nombre'       => 'Tostado jamón y queso',
            'descripcion'  => 'Pan de campo, jamón cocido y queso',
            'precio'       => 5800,
        ]);
        Producto::create([
            'categoria_id' => $ensaladas->id,
            'nombre'       => 'Ensalada césar',
            'descripcion'  => 'Lechuga, pollo grillado, crutones',
            'precio'       => 5400,
        ]);
        Producto::create([
            'categoria_id' => $postres->id,
            'nombre'       => 'Medialuna x3',
            'descripcion'  => 'Manteca, recién horneadas',
            'precio'       => 2500,
        ]);

        $this->call(HorarioSeeder::class);
    }
}