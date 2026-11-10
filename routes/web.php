<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\Admin\ProductoController as AdminProductoController; // Esto es para evitar conflictos de nombres con el controlador de productos del administrador
use App\Http\Controllers\Admin\UsuarioController as AdminUsuarioController;
use App\Http\Controllers\Admin\HorarioController as AdminHorarioController;
use App\Models\Horario;


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

Route::get('/nosotros', function () {
    return view('nosotros', [
        'horarios' => Horario::orderBy('dia')->orderBy('apertura')->get(),
    ]);
})->name('nosotros');

// Rutas para usuarios autenticados con rol de administrador
Route::middleware(['auth', 'rol:administrador'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', fn() => view('admin.dashboard'))->name('dashboard'); // Esto muestra el dashboard del administrador
        Route::resource('categorias', CategoriaController::class)->except('show'); // Esto crea rutas para index, create, store, edit, update y destroy
        Route::resource('productos', AdminProductoController::class)->except('show'); // Esto crea rutas para index, create, store, edit, update y destroy
        Route::resource('usuarios', AdminUsuarioController::class)->except('show'); // Esto crea rutas para index, create, store, edit, update y destroy
        Route::resource('horarios', AdminHorarioController::class)->except('show'); // Esto crea rutas para index, create, store, edit, update y destroy
    });
