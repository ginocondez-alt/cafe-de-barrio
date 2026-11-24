<?php

use App\Http\Controllers\Admin\HorarioController as AdminHorarioController;
use App\Http\Controllers\Admin\ProductoController as AdminProductoController; // Esto es para evitar conflictos de nombres con el controlador de productos del administrador
use App\Http\Controllers\Admin\UsuarioController as AdminUsuarioController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Caja\PedidoController as CajaPedidoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\Cocina\PedidoController as CocinaPedidoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use App\Models\Horario;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\Admin\SolicitudController as AdminSolicitudController;

// Rutas para la página de inicio
Route::view('/', 'inicio')->name('inicio');

// Ruta para la página Menú
Route::get('/menu', [ProductoController::class, 'index'])->name('menu.index');

// Rutas para usuarios no autenticados
Route::middleware('guest')->group(function () {
    Route::get('/registro', [AuthController::class, 'mostrarRegistro'])->name('registro');
    Route::post('/registro', [AuthController::class, 'registrar']);

    Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Rutas para usuarios autenticados
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// Rutas de pedidos para usuarios autenticados
Route::middleware('auth')->group(function () {
    Route::get('/mis-pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
    Route::post('/mi-pedido/agregar/{producto}', [PedidoController::class, 'agregar'])->name('pedidos.agregar');
    Route::delete('/mi-pedido/items/{item}', [PedidoController::class, 'quitar'])->name('pedidos.quitar');
    Route::patch('/mis-pedidos/{pedido}/confirmar', [PedidoController::class, 'confirmar'])->name('pedidos.confirmar');
});

Route::get('/nosotros', function () {
    return view('nosotros', [
        'horarios' => Horario::orderBy('dia')->orderBy('apertura')->get(),
    ]);
})->name('nosotros');

Route::post('/solicitudes', [SolicitudController::class, 'store'])
    ->name('solicitudes.store');

// Rutas para usuarios autenticados con rol de administrador
Route::middleware(['auth', 'rol:administrador'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', fn () => view('admin.dashboard'))->name('dashboard'); // Esto muestra el dashboard del administrador
        Route::resource('categorias', CategoriaController::class)->except('show'); // Esto crea rutas para index, create, store, edit, update y destroy
        Route::resource('productos', AdminProductoController::class)->except('show'); // Esto crea rutas para index, create, store, edit, update y destroy
        Route::resource('usuarios', AdminUsuarioController::class)->except('show'); // Esto crea rutas para index, create, store, edit, update y destroy
        Route::resource('horarios', AdminHorarioController::class)->except('show'); // Esto crea rutas para index, create, store, edit, update y destroy
        Route::get('/solicitudes', [AdminSolicitudController::class, 'index'])->name('solicitudes.index'); //esta ruta para que el administrador pueda acceder a la sección de solicitudes. 
    });

// Rutas para usuarios con rol de cocina
Route::middleware(['auth', 'rol:cocina'])
    ->prefix('cocina')
    ->name('cocina.')
    ->group(function () {
        Route::get('/pedidos', [CocinaPedidoController::class, 'index'])->name('pedidos.index');
        Route::patch('/pedidos/{pedido}/estado', [CocinaPedidoController::class, 'actualizarEstado'])->name('pedidos.estado');
    });

// Rutas para usuarios con rol de caja
Route::middleware(['auth', 'rol:caja'])
    ->prefix('caja')
    ->name('caja.')
    ->group(function () {
        Route::get('/pedidos', [CajaPedidoController::class, 'index'])->name('pedidos.index');
        Route::patch('/pedidos/{pedido}/estado', [CajaPedidoController::class, 'actualizarEstado'])->name('pedidos.estado');
    });
