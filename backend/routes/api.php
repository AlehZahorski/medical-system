<?php

use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

/*
 * AUTH
 */
Route::post('/login', [AuthController::class, 'loginAction']);
Route::middleware('auth:sanctum')
    ->post('/logout', [AuthController::class, 'logoutAction']);

/*
 * ORDER
 */
Route::middleware('auth:sanctum')
    ->get('/results', [OrderController::class, 'getOrderListAction']);
