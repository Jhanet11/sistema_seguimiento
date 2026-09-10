<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\ObservacionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReparacionController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

// --- Ruta raíz: redirige al login o al dashboard ---
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// --- Dashboard: contenido distinto según rol ---
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])->name('dashboard');

// --- Rutas compartidas (autenticado, cualquier rol) ---
Route::middleware('auth')->group(function () {
    Route::get('/reparaciones', [ReparacionController::class, 'index'])->name('reparaciones.index');
    Route::get('/reparaciones/{reparacion}', [ReparacionController::class, 'show'])->name('reparaciones.show');
    Route::get('/equipos', [EquipoController::class, 'index'])->name('equipos.index');
    Route::get('/equipos/{equipo}', [EquipoController::class, 'show'])->name('equipos.show');

    // --- Rutas del perfil del usuario (Breeze) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- Rutas solo Admin ---
Route::middleware(['auth', 'rol:admin'])->group(function () {
    Route::get('/reparaciones-crear', [ReparacionController::class, 'create'])->name('reparaciones.create');
    Route::post('/reparaciones', [ReparacionController::class, 'store'])->name('reparaciones.store');
    Route::post('/reparaciones/{reparacion}/asignar', [ReparacionController::class, 'asignar'])->name('reparaciones.asignar');
    Route::get('/reparaciones/{reparacion}/comprobante', [ReparacionController::class, 'comprobante'])->name('reparaciones.comprobante');

    Route::get('/equipos-crear', [EquipoController::class, 'create'])->name('equipos.create');
    Route::post('/equipos', [EquipoController::class, 'store'])->name('equipos.store');

    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::get('/clientes-crear', [ClienteController::class, 'create'])->name('clientes.create');
    Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
    Route::get('/clientes/{cliente}', [ClienteController::class, 'show'])->name('clientes.show');
    Route::get('/clientes/{cliente}/historial-pdf', [ClienteController::class, 'historialPdf'])->name('clientes.historial.pdf');

    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/generar', [ReporteController::class, 'generar'])->name('reportes.generar');

    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios-crear', [UsuarioController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::patch('/usuarios/{usuario}/toggle', [UsuarioController::class, 'toggleActivo'])->name('usuarios.toggle');
});

// --- Rutas Admin + Técnico ---
Route::middleware(['auth', 'rol:admin,tecnico'])->group(function () {
    Route::patch('/reparaciones/{reparacion}/estado', [ReparacionController::class, 'actualizarEstado'])
        ->name('reparaciones.estado');
    Route::post('/reparaciones/{reparacion}/observaciones', [ObservacionController::class, 'store'])
        ->name('observaciones.store');
    Route::post('/reparaciones/{reparacion}/repuestos', [ReparacionController::class, 'agregarRepuesto'])
        ->name('reparaciones.repuestos.store');
});

// --- Rutas solo Técnico ---
Route::middleware(['auth', 'rol:tecnico'])->group(function () {
    Route::post('/reparaciones/{reparacion}/autoasignar', [ReparacionController::class, 'autoasignar'])
        ->name('reparaciones.autoasignar');
});

require __DIR__.'/auth.php';