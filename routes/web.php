<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;


//  ОСНОВНЫЕ МАРШРУТЫ ПРИЛОЖЕНИЯ
Route::get('/', [MainController::class, 'index'])->name('home');
Route::get('/category/{id}', [MainController::class, 'category'])->name('category');

//  МАРШРУТЫ, ДОСТУПНЫЕ ТОЛЬКО ПОСЛЕ АВТОРИЗАЦИИ
Route::middleware('auth')->group(function () {
    // Кабинет и редактирование (Для ведущего)
    Route::get('/cabinet', [MainController::class, 'cabinet'])->name('cabinet');
    Route::get('/cabinet/create', [MainController::class, 'createMC'])->name('mc.create');
    Route::post('/cabinet/store', [MainController::class, 'storeMC'])->name('mc.store');
    Route::get('/cabinet/edit/{id}', [MainController::class, 'editMC'])->name('mc.edit');
    Route::put('/cabinet/update/{id}', [MainController::class, 'updateMC'])->name('mc.update');

    // Бронирование (Для посетителя)
    Route::get('/book/confirm/{id}', [MainController::class, 'confirmBooking'])->name('book.confirm');
    Route::post('/book/store/{id}', [MainController::class, 'storeBooking'])->name('book.store');
});

//  МАРШРУТЫ АВТОРИЗАЦИИ (BREEZE)
require __DIR__.'/auth.php';