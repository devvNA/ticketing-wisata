<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::post('/login/google', [AuthController::class, 'loginGoogle']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/events', [EventController::class, 'index']);

Route::get('/event-categories', [EventController::class, 'categories']);

Route::get('/event/{event_id}', [EventController::class, 'detail']);

Route::post('/order', [OrderController::class, 'create'])->middleware('auth:sanctum');
