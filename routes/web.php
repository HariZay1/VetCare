<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PropietarioController;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\VeterinarioController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\TratamientoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rutas de Admin y Recepción
    Route::middleware(['can:gestionar_propietarios'])->group(function () {
        Route::resource('propietarios', PropietarioController::class);
        Route::resource('citas', CitaController::class);
        
        Route::get('citas/export/excel', [CitaController::class, 'exportExcel'])->name('citas.export.excel');
        Route::get('citas/export/pdf', [CitaController::class, 'exportPdf'])->name('citas.export.pdf');
        Route::get('citas/{cita}/pdf', [CitaController::class, 'generarPdfCita'])->name('citas.pdf');
    });
    
    // Rutas solo de Admin 
    Route::middleware(['can:gestionar_veterinarios'])->group(function () {
        Route::resource('veterinarios', VeterinarioController::class);
        Route::get('reportes', [DashboardController::class, 'reportes'])->name('reportes.index');
    });
    
    // Rutas de Veterinarios 
    Route::middleware(['can:gestionar_tratamientos'])->group(function () {
        Route::resource('tratamientos', TratamientoController::class);
        Route::get('mi-agenda', [CitaController::class, 'miAgenda'])->name('veterinario.agenda');
        Route::post('citas/{cita}/completar', [CitaController::class, 'completar'])->name('citas.completar');
    });
    
    //  Rutas de Clientes
    Route::get('mis-mascotas', [MascotaController::class, 'misMascotas'])->name('cliente.mascotas');
    Route::resource('mascotas', MascotaController::class); // ← SOLO ESTA LÍNEA
    Route::get('mis-citas', [CitaController::class, 'misCitas'])->name('cliente.citas');
    Route::post('citas/solicitar', [CitaController::class, 'solicitar'])->name('citas.solicitar');
});

require __DIR__.'/auth.php';