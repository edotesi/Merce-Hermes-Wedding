<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GiftController;
use App\Http\Controllers\DressCodeController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\AccommodationController;
use App\Http\Controllers\GalleryController;

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
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule');
    Route::get('/dress-code', [DressCodeController::class, 'index'])->name('dress-code');
    Route::get('/alojamientos', [AccommodationController::class, 'index'])->name('accommodations');

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
    Route::get('/nuestra-historia', [App\Http\Controllers\StoryController::class, 'index'])->name('story');
    Route::get('/calendar.ics', function () {
        $event = [
            'begin' => '2025-06-14T17:00:00',
            'end' => '2025-06-14T23:00:00',
            'title' => 'Boda Mercè & Hermes',
            'description' => 'Celebración de la boda de Mercè y Hermes',
            'location' => 'Barcelona, Spain'
        ];

        $icsContent = "BEGIN:VCALENDAR\n";
        $icsContent .= "VERSION:2.0\n";
        $icsContent .= "BEGIN:VEVENT\n";
        $icsContent .= "DTSTART:" . date('Ymd\THis\Z', strtotime($event['begin'])) . "\n";
        $icsContent .= "DTEND:" . date('Ymd\THis\Z', strtotime($event['end'])) . "\n";
        $icsContent .= "SUMMARY:" . $event['title'] . "\n";
        $icsContent .= "DESCRIPTION:" . $event['description'] . "\n";
        $icsContent .= "LOCATION:" . $event['location'] . "\n";
        $icsContent .= "END:VEVENT\n";
        $icsContent .= "END:VCALENDAR";

        return response($icsContent)
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="calendar.ics"');
    })->name('calendar.ics');

    // Rutas para galería
// Rutas para galería
    Route::get('/galeria', [GalleryController::class, 'index'])->name('gallery.index');
    Route::get('/galeria/download/{id}', [GalleryController::class, 'download'])->name('gallery.download');
    Route::get('/galeria/download-all', [GalleryController::class, 'downloadAll'])->name('gallery.downloadAll');
}
