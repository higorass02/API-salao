<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CollaboratorController;
use App\Http\Controllers\ScheduleController;

// Rota REST completa para Users
Route::apiResource('users', UserController::class);

// Rota REST completa para Services
Route::apiResource('services', ServiceController::class);

// Rota REST completa para Appointments
Route::apiResource('appointments', AppointmentController::class);

// Rota REST completa para Collaborators
Route::apiResource('collaborators', CollaboratorController::class);

// Rota REST completa para Schedules
Route::apiResource('schedules', ScheduleController::class);
