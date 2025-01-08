<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GiftController;
use App\Http\Controllers\InfoController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('gifts')->group(function () {
   Route::get('/', [GiftController::class, 'index'])->name('gifts.index');
   Route::post('/{gift}/purchase', [GiftController::class, 'markAsPurchased'])->name('gifts.purchase');
   Route::post('/{gift}/unpurchase', [GiftController::class, 'unmarkAsPurchased'])->name('gifts.unpurchase');
});

Route::get('/info', [InfoController::class, 'index'])->name('info');
