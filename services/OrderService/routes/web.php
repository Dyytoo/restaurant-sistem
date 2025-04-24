<?php

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [OrderController::class, 'welcome']);
Route::patch('/orders/{order}/update-status', [FrontendController::class, 'updateOrderStatus'])->name('orders.update-status');
Route::delete('/orders/{order}', [FrontendController::class, 'destroyOrder'])->name('orders.destroy');