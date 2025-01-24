<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GiftController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\MaintenanceController;

// Variables desde el .env
$appMode = env('MAINTENANCE_MODE', 'false'); // Modo de la aplicación
$maintenanceToken = env('MAINTENANCE_TOKEN', ''); // Token de mantenimiento

// Verificar si la URL contiene el parámetro de preview con el valor correcto
if (request()->query('preview') === $maintenanceToken) {
    // Si es así, se redirige al flujo normal
    $appMode = 'false';
}

if ($appMode == 'true') {
    // Modo mantenimiento
    Route::get('/', [MaintenanceController::class, 'show'])->name('home');
    Route::fallback([MaintenanceController::class, 'show']);
} else {
    // Modo activo
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/info', [InfoController::class, 'index'])->name('info');
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule');

    Route::prefix('gifts')->group(function () {
        // Vista principal
        Route::get('/', [GiftController::class, 'index'])->name('gifts.index');

        // Rutas para reserva
        Route::post('/{gift}/reserve', [GiftController::class, 'reserve'])->name('gifts.reserve');
        Route::get('/{gift}/confirm/{code}', [GiftController::class, 'showConfirmForm'])->name('gifts.confirm');
        Route::post('/{gift}/confirm', [GiftController::class, 'confirmPurchase'])->name('gifts.confirmPurchase');

        // Rutas para cancelación
        Route::get('/{gift}/cancel/{code}', [GiftController::class, 'showCancelForm'])->name('gifts.cancel');
        Route::post('/{gift}/cancel', [GiftController::class, 'cancelReservation'])->name('gifts.cancelReservation');
    });
}
