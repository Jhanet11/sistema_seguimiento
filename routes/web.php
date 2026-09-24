<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\ObservacionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReparacionController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\SeguimientoController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => auth()->check() ? redirect()->route('dashboard') : redirect()->route('login'));
Route::get('/seguimiento', [SeguimientoController::class, 'index'])->name('seguimiento.index');
Route::post('/seguimiento', [SeguimientoController::class, 'buscar'])->middleware('throttle:10,1')->name('seguimiento.buscar');
Route::get('/seguimiento/{codigo}', [SeguimientoController::class, 'show'])->middleware('throttle:30,1')->name('seguimiento.show');
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/reparaciones', [ReparacionController::class, 'index'])->name('reparaciones.index');
    Route::get('/reparaciones/{reparacion}', [ReparacionController::class, 'show'])->whereNumber('reparacion')->name('reparaciones.show');
    Route::get('/equipos', [EquipoController::class, 'index'])->name('equipos.index');
    Route::get('/equipos/{equipo}', [EquipoController::class, 'show'])->whereNumber('equipo')->name('equipos.show');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
Route::middleware(['auth', 'rol:admin,tecnico'])->group(function () {
    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::get('/clientes-crear', [ClienteController::class, 'create'])->name('clientes.create');
    Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
    Route::get('/clientes/{cliente}', [ClienteController::class, 'show'])->whereNumber('cliente')->name('clientes.show');
    Route::get('/clientes/{cliente}/historial-pdf', [ClienteController::class, 'historialPdf'])->name('clientes.historial.pdf');
    Route::get('/equipos-crear', [EquipoController::class, 'create'])->name('equipos.create');
    Route::post('/equipos', [EquipoController::class, 'store'])->name('equipos.store');
    Route::get('/reparaciones-crear', [ReparacionController::class, 'create'])->name('reparaciones.create');
    Route::post('/reparaciones', [ReparacionController::class, 'store'])->name('reparaciones.store');
    Route::patch('/reparaciones/{reparacion}', [ReparacionController::class, 'update'])->name('reparaciones.update');
    Route::get('/reparaciones/{reparacion}/comprobante', [ReparacionController::class, 'comprobante'])->name('reparaciones.comprobante');
    Route::patch('/reparaciones/{reparacion}/estado', [ReparacionController::class, 'actualizarEstado'])->name('reparaciones.estado');
    Route::post('/reparaciones/{reparacion}/observaciones', [ObservacionController::class, 'store'])->name('observaciones.store');
    Route::post('/reparaciones/{reparacion}/repuestos', [ReparacionController::class, 'agregarRepuesto'])->name('reparaciones.repuestos.store');
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/generar', [ReporteController::class, 'generar'])->name('reportes.generar');
});
Route::middleware(['auth', 'rol:admin'])->group(function () {
    Route::get('/clientes/{cliente}/editar', [ClienteController::class, 'edit'])->name('clientes.edit');
    Route::patch('/clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');
    Route::delete('/clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy');
    Route::get('/equipos/{equipo}/editar', [EquipoController::class, 'edit'])->name('equipos.edit');
    Route::patch('/equipos/{equipo}', [EquipoController::class, 'update'])->name('equipos.update');
    Route::delete('/equipos/{equipo}', [EquipoController::class, 'destroy'])->name('equipos.destroy');
    Route::delete('/reparaciones/{reparacion}', [ReparacionController::class, 'destroy'])->name('reparaciones.destroy');
    Route::post('/reparaciones/{reparacion}/asignar', [ReparacionController::class, 'asignar'])->name('reparaciones.asignar');
    Route::resource('usuarios', UsuarioController::class)->except('show');
    Route::patch('/usuarios/{usuario}/toggle', [UsuarioController::class, 'toggleActivo'])->name('usuarios.toggle');
});
Route::post('/reparaciones/{reparacion}/autoasignar',[ReparacionController::class, 'autoasignar'])->middleware(['auth', 'rol:tecnico'])->name('reparaciones.autoasignar');
require __DIR__.'/auth.php';
