<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PropietarioController;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\VeterinarioController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\TratamientoController;
use App\Http\Controllers\ReporteController;

// Ruta pública
Route::get('/', function () {
    return view('welcome');
});

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    
    // Dashboard según rol
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rutas de Admin y Recepción
    Route::middleware(['role:admin|recepcion'])->group(function () {
        Route::resource('propietarios', PropietarioController::class);
        Route::resource('citas', CitaController::class);

        // Exportaciones
        Route::get('propietarios/export/excel', [PropietarioController::class, 'exportExcel'])->name('propietarios.export.excel');
        Route::get('propietarios/export/pdf', [PropietarioController::class, 'exportPdf'])->name('propietarios.export.pdf');

        Route::get('mascotas/export/excel', [MascotaController::class, 'exportExcel'])->name('mascotas.export.excel');
        Route::get('mascotas/export/pdf', [MascotaController::class, 'exportPdf'])->name('mascotas.export.pdf');

        Route::get('citas/export/excel', [CitaController::class, 'exportExcel'])->name('citas.export.excel');
        Route::get('citas/export/pdf', [CitaController::class, 'exportPdf'])->name('citas.export.pdf');
        
    });
    
    // Rutas solo de Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('veterinarios', VeterinarioController::class);
        Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
        // Gestión de usuarios (no da aun :c)
           Route::get('admin/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
           Route::get('admin/users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('admin.users.create');
           Route::post('admin/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.users.store');
           Route::get('admin/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('admin.users.show');
           Route::get('admin/users/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('admin.users.edit');
           Route::put('admin/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.users.update');
           Route::put('admin/users/{user}/role', [App\Http\Controllers\Admin\UserController::class, 'updateRole'])->name('admin.users.updateRole');
        // Export veterinarios
        Route::get('veterinarios/export/excel', [VeterinarioController::class, 'exportExcel'])->name('veterinarios.export.excel');
        Route::get('veterinarios/export/pdf', [VeterinarioController::class, 'exportPdf'])->name('veterinarios.export.pdf');
    });
    
    // Rutas de Veterinarios
    Route::middleware(['role:veterinario|admin'])->group(function () {
        Route::resource('tratamientos', TratamientoController::class);
        Route::get('tratamientos/export/excel', [TratamientoController::class, 'exportExcel'])->name('tratamientos.export.excel');
        Route::get('tratamientos/export/pdf', [TratamientoController::class, 'exportPdf'])->name('tratamientos.export.pdf');
        Route::get('mi-agenda', [CitaController::class, 'miAgenda'])->name('veterinario.agenda');
        Route::get('veterinario/citas', [CitaController::class, 'indexVeterinario'])->name('veterinario.citas');
        Route::get('veterinario/citas/{cita}', [CitaController::class, 'showVeterinario'])->name('veterinario.citas.show');
        Route::post('citas/{cita}/completar', [CitaController::class, 'completar'])->name('citas.completar');
    });
    
    // Rutas de Clientes
    Route::middleware(['role:cliente'])->group(function () {
        // Usar el método misMascotas (método pensado para clientes) en lugar de index
        Route::get('mis-mascotas', [MascotaController::class, 'misMascotas'])->name('cliente.mascotas');
        // Exportaciones para clientes (solo sus mascotas)
        Route::get('mis-mascotas/export/excel', [MascotaController::class, 'exportExcelClient'])->name('cliente.mascotas.export.excel');
        Route::get('mis-mascotas/export/pdf', [MascotaController::class, 'exportPdfClient'])->name('cliente.mascotas.export.pdf');
        Route::post('citas/solicitar', [CitaController::class, 'solicitar'])->name('citas.solicitar');
        // Completar / editar perfil de propietario para clientes recién registrados
        Route::get('propietario/complete', [PropietarioController::class, 'createCliente'])->name('propietarios.complete');
        Route::post('propietario/complete', [PropietarioController::class, 'storeCliente'])->name('propietarios.complete.store');
        Route::get('propietario/editar', [PropietarioController::class, 'editCliente'])->name('propietarios.edit.mi');
        Route::put('propietario', [PropietarioController::class, 'updateCliente'])->name('propietarios.update.mi');
    });

    // Permitir a propietarios autenticados ver sus citas (no depender exclusivamente del role:cliente)
    Route::get('mis-citas', [CitaController::class, 'misCitas'])->name('cliente.citas');
    // Ruta para que un propietario descargue el PDF de SU cita
    Route::get('cliente/citas/{cita}/pdf', [CitaController::class, 'generarPdfCitaCliente'])->name('cliente.citas.pdf');
    // Ruta canónica para generar PDF de una cita (admin/recepcion o propietario dueño)
    Route::get('citas/{cita}/pdf', [CitaController::class, 'generarPdfCita'])->name('citas.pdf');

    
    Route::get('mascotas/create', [MascotaController::class, 'create'])->name('mascotas.create');
    Route::post('mascotas', [MascotaController::class, 'store'])->name('mascotas.store');
    Route::get('mascotas/{mascota}', [MascotaController::class, 'show'])->name('mascotas.show');
    Route::get('mascotas/{mascota}/edit', [MascotaController::class, 'edit'])->name('mascotas.edit');
    Route::put('mascotas/{mascota}', [MascotaController::class, 'update'])->name('mascotas.update');

    Route::resource('mascotas', MascotaController::class)->only(['index','destroy']);
});

require __DIR__.'/auth.php';