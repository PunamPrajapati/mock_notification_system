<?php

use App\Http\Controllers\NotificationController;
use App\Http\Middleware\RateLimitMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/notifications', [NotificationController::class, 'store'])->middleware(RateLimitMiddleware::class);
Route::patch('/notifications/{id}', [NotificationController::class, 'updateStatus']);
Route::get('/notifications/recent', [NotificationController::class, 'recent']);
Route::get('/notifications/summary', [NotificationController::class, 'summary']);