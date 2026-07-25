<?php
// Extrait à ajouter dans routes/api/v1/public.php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

// --- Auth utilisateur lambda ---
Route::post('/register', [RegisterController::class, 'store']);
Route::post('/login', [LoginController::class, 'store']);
Route::post('/logout', [LogoutController::class, 'destroy'])->middleware('auth:sanctum');

// --- Mot de passe oublié (accessible à tous les rôles) ---
Route::post('/password/request-reset', [PasswordResetController::class, 'requestReset'])
    ->middleware('otp.throttle');
Route::post('/password/reset', [PasswordResetController::class, 'resetPassword']);