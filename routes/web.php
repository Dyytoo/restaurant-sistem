<?php

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;


Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::post('/place-order', [FrontendController::class, 'placeOrder'])->name('place.order');
Route::patch('/orders/{order}/update-status', [FrontendController::class, 'updateOrderStatus'])->name('orders.update-status');
     
Route::delete('/orders/{order}', [FrontendController::class, 'destroyOrder'])->name('orders.destroy');
// routes/web.php

// routes/web.php

Route::get('/menus/create', [MenuController::class, 'create'])->name('menus.create');
Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');
Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');