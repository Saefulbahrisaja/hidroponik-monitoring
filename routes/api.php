<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SensorController;
use App\Http\Controllers\Api\AuthController;


Route::post('/sensor', [SensorController::class, 'store']);
    

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/batas', [SensorController::class, 'getBatas']);
Route::post('/update-batas', [SensorController::class, 'updateBatas']);
Route::get('/sensor/export', [SensorController::class, 'export']);
Route::get('/sensor/filter', [SensorController::class, 'filterData']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
