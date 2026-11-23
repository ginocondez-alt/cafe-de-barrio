<?php

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(LazilyRefreshDatabase::class);

/**
 * @return array{User, Categoria}
 */
function administradorConCategoria(): array
{
    $administrador = User::factory()->create([
        'apellido' => 'Café',
        'telefono' => '0000000000',
        'direccion' => 'Local',
    ]);
    $administrador->forceFill(['rol' => 'administrador'])->save();

    return [$administrador, Categoria::create(['nombre' => 'Cafés'])];
}

/**
 * @return array<string, mixed>
 */
function datosProducto(Categoria $categoria): array
{
    return [
        'categoria_id' => $categoria->id,
        'nombre' => 'Café con leche',
        'descripcion' => 'Espresso doble con leche vaporizada',
        'precio' => 3200,
    ];
}

it('guarda la imagen JPEG en productos al crear un producto', function () {
    Storage::fake('public');
    [$administrador, $categoria] = administradorConCategoria();
    $imagen = UploadedFile::fake()->image('cafe.jpeg', 100, 100)->size(100);

    $respuesta = $this->actingAs($administrador)->post(
        route('admin.productos.store'),
        datosProducto($categoria) + ['imagen' => $imagen]
    );

    $producto = Producto::where('nombre', 'Café con leche')->firstOrFail();

    $respuesta->assertRedirect(route('admin.productos.index'));
    expect($producto->imagen)->toStartWith('productos/')->toEndWith('.jpeg');
    Storage::disk('public')->assertExists($producto->imagen);
});

it('crea un producto sin imagen cuando no se sube ninguna', function () {
    Storage::fake('public');
    [$administrador, $categoria] = administradorConCategoria();

    $respuesta = $this->actingAs($administrador)->post(
        route('admin.productos.store'),
        datosProducto($categoria)
    );

    $producto = Producto::where('nombre', 'Café con leche')->firstOrFail();

    $respuesta->assertRedirect(route('admin.productos.index'));
    expect($producto->imagen)->toBeNull();
});

it('reemplaza la imagen al editar un producto', function () {
    Storage::fake('public');
    [$administrador, $categoria] = administradorConCategoria();
    Storage::disk('public')->put('productos/anterior.jpeg', 'imagen anterior');

    $producto = Producto::create(datosProducto($categoria) + [
        'imagen' => 'productos/anterior.jpeg',
    ]);
    $nuevaImagen = UploadedFile::fake()->image('nueva.jpeg', 100, 100);

    $respuesta = $this->actingAs($administrador)->put(
        route('admin.productos.update', $producto),
        datosProducto($categoria) + ['imagen' => $nuevaImagen]
    );

    $producto->refresh();

    $respuesta->assertRedirect(route('admin.productos.index'));
    expect($producto->imagen)->toStartWith('productos/')->not->toBe('productos/anterior.jpeg');
    Storage::disk('public')->assertExists($producto->imagen);
    Storage::disk('public')->assertMissing('productos/anterior.jpeg');
});

it('conserva la imagen actual al editar sin subir otra', function () {
    Storage::fake('public');
    [$administrador, $categoria] = administradorConCategoria();

    $producto = Producto::create(datosProducto($categoria) + [
        'imagen' => 'productos/actual.jpeg',
    ]);
    Storage::disk('public')->put('productos/actual.jpeg', 'imagen actual');

    $respuesta = $this->actingAs($administrador)->put(
        route('admin.productos.update', $producto),
        datosProducto($categoria)
    );

    $producto->refresh();

    $respuesta->assertRedirect(route('admin.productos.index'));
    expect($producto->imagen)->toBe('productos/actual.jpeg');
    Storage::disk('public')->assertExists('productos/actual.jpeg');
});

it('muestra en el menú la imagen guardada en storage', function () {
    $categoria = Categoria::create(['nombre' => 'Cafés']);
    Producto::create(datosProducto($categoria) + [
        'imagen' => 'productos/cafe-con-leche.jpeg',
    ]);

    $respuesta = $this->get(route('menu.index'));

    $respuesta->assertOk();
    $respuesta->assertSee(Storage::url('productos/cafe-con-leche.jpeg'), false);
});

it('rechaza una imagen que no es JPEG', function () {
    Storage::fake('public');
    [$administrador, $categoria] = administradorConCategoria();
    $imagen = UploadedFile::fake()->image('cafe.png', 100, 100);

    $respuesta = $this->actingAs($administrador)->post(
        route('admin.productos.store'),
        datosProducto($categoria) + ['imagen' => $imagen]
    );

    $respuesta->assertSessionHasErrors('imagen');
    expect(Producto::count())->toBe(0);
    Storage::disk('public')->assertEmpty();
});
