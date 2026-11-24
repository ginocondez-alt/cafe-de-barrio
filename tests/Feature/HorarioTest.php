<?php

use App\Models\Horario;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

it('formatea el rango de horarios con AM y PM', function () {
    $horario = Horario::make([
        'dia' => 1,
        'apertura' => '09:00:00',
        'cierre' => '18:00:00',
        'activo' => true,
    ]);

    expect($horario->rango())->toBe('09:00 AM - 06:00 PM');
});

it('usa AM y PM correctos en mediodía y medianoche', function () {
    $horario = Horario::make([
        'dia' => 7,
        'apertura' => '12:00:00',
        'cierre' => '00:00:00',
        'activo' => true,
    ]);

    expect($horario->rango())->toBe('12:00 PM - 12:00 AM');
});

it('muestra cerrado cuando el día está inactivo', function () {
    $horario = Horario::make([
        'dia' => 7,
        'activo' => false,
    ]);

    expect($horario->rango())->toBe('Cerrado');
});

it('muestra los horarios con AM y PM en el panel y en Nosotros', function () {
    $administrador = User::factory()->create([
        'apellido' => 'Admin',
        'telefono' => '0000000000',
        'direccion' => 'Local',
        'rol' => 'administrador',
    ]);

    Horario::create([
        'dia' => 1,
        'apertura' => '09:00',
        'cierre' => '18:00',
        'activo' => true,
    ]);

    $this->actingAs($administrador)
        ->get(route('admin.horarios.index'))
        ->assertOk()
        ->assertSee('09:00 AM')
        ->assertSee('06:00 PM');

    $this->get(route('nosotros'))
        ->assertOk()
        ->assertSee('09:00 AM')
        ->assertSee('06:00 PM');
});
