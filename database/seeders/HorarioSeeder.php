<?php

namespace Database\Seeders;

use App\Models\Horario;
use Illuminate\Database\Seeder;

class HorarioSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([1, 2, 3, 4] as $dia) {
            Horario::create(['dia' => $dia, 'apertura' => '09:00', 'cierre' => '16:00', 'activo' => true]);
        }

        foreach ([5, 6] as $dia) {
            Horario::create(['dia' => $dia, 'apertura' => '09:00', 'cierre' => '18:00', 'activo' => true]);
        }

        Horario::create(['dia' => 7, 'activo' => false]);
    }
}