<?php

use App\Http\Controllers\MenuController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/menus', [MenuController::class, 'store']);
Route::apiResource('menus', MenuController::class);
Route::get('menus/category/{category}', [MenuController::class, 'getByCategory']);

Route::get('/menus', [MenuController::class, 'index']);
Route::get('/menus/{id}', [MenuController::class, 'show']);
Route::get('/menus/category/{category}', [MenuController::class, 'getByCategory']);