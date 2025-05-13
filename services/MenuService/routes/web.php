<?php

use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;

// Route untuk tampilan web
Route::get('/', [MenuController::class, 'welcome'])->name('menu.dashboard');
Route::get('/menus/create', [MenuController::class, 'create'])->name('menus.create');
Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');
Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');

// Route untuk API
Route::prefix('api')->group(function () {
    Route::get('/menus', [MenuController::class, 'index']);
    Route::get('/menus/{id}', [MenuController::class, 'show']);
    Route::get('/menus/category/{category}', [MenuController::class, 'getByCategory']);
});