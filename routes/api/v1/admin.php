<?php

use App\Http\Controllers\Api\Admin\CountryController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\FieldOfStudyController;
use App\Http\Controllers\Api\Admin\PartnerController;
use App\Http\Controllers\Api\Admin\PostController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\ScholarshipController;
use App\Http\Controllers\Api\Admin\ScholarshipTypeController;
use App\Http\Controllers\Api\Admin\ServiceController;
use App\Http\Controllers\Api\Admin\StatsController;
use App\Http\Controllers\Api\Admin\StudyLevelController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Auth\OtpController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth back-office (OTP en 2 étapes) — hors du groupe auth:sanctum,
| c'est justement ce qui permet d'obtenir le token.
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    Route::post('/login', [OtpController::class, 'requestLoginOtp'])
        ->middleware('otp.throttle');
    Route::post('/login/verify', [OtpController::class, 'verifyLoginOtp']);
});

/*
|--------------------------------------------------------------------------
| Back-office — accessible à Admin ET Rédacteur
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['auth:sanctum', 'role:admin,redacteur'])
    ->group(function () {

        // --- Dashboard & stats ---
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::get('/stats/by-country', [StatsController::class, 'byCountry']);
        Route::get('/stats/by-field', [StatsController::class, 'byField']);

        // --- Bourses ---
        Route::apiResource('scholarships', ScholarshipController::class);
        Route::patch('/scholarships/{scholarship}/publish', [ScholarshipController::class, 'publish']);
        Route::patch('/scholarships/{scholarship}/archive', [ScholarshipController::class, 'archive']);

        // --- Référentiels ---
        Route::apiResource('study-levels', StudyLevelController::class);
        Route::apiResource('scholarship-types', ScholarshipTypeController::class);
        Route::apiResource('fields-of-study', FieldOfStudyController::class);
        Route::apiResource('countries', CountryController::class);

        // --- Contenu ---
        Route::apiResource('posts', PostController::class);
        Route::patch('/posts/{post}/publish', [PostController::class, 'publish']);
        Route::patch('/posts/{post}/archive', [PostController::class, 'archive']);

        Route::apiResource('partners', PartnerController::class);
        Route::apiResource('services', ServiceController::class);
        Route::apiResource('products', ProductController::class);
    });

/*
|--------------------------------------------------------------------------
| Back-office — réservé à l'Admin (gestion des utilisateurs/rôles)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['auth:sanctum', 'role:admin'])
    ->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::patch('/users/{user}/role', [UserController::class, 'updateRole']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);
    });