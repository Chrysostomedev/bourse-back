<?php
// Extrait à ajouter dans routes/api/v1/admin.php

use App\Http\Controllers\Api\Auth\OtpController;
use Illuminate\Support\Facades\Route;

// --- Connexion admin/rédacteur en 2 étapes (email+mdp, puis OTP) ---
// Placé hors du groupe protégé par auth:sanctum : c'est justement
// ce qui permet d'obtenir le token.
Route::prefix('admin')->group(function () {
    Route::post('/login', [OtpController::class, 'requestLoginOtp'])
        ->middleware('otp.throttle');
    Route::post('/login/verify', [OtpController::class, 'verifyLoginOtp']);
});

// Rappel : toutes les routes de CRUD back-office restent sous
// ->middleware(['auth:sanctum', 'role:admin,redacteur'])
// et la gestion des utilisateurs sous ->middleware('role:admin') seul.