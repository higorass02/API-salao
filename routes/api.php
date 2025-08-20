<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CollaboratorController;
use App\Http\Controllers\ScheduleController;

use App\Http\Controllers\AuthController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function(){
    Route::post('logout', [AuthController::class, 'logout']);
    
    // Exemplo de rotas protegidas
    Route::apiResource('users', UserController::class);
    Route::apiResource('services', ServiceController::class);
    Route::apiResource('appointments', AppointmentController::class);
    Route::apiResource('collaborators', CollaboratorController::class);
    Route::apiResource('schedules', ScheduleController::class);
});

