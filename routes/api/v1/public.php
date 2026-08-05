<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Public\CommentController;
use App\Http\Controllers\Api\Public\LikeController;
use App\Http\Controllers\Api\Public\PartnerController;
use App\Http\Controllers\Api\Public\PostController;
use App\Http\Controllers\Api\Public\DashboardController;
use App\Http\Controllers\Api\Public\ProductController;
use App\Http\Controllers\Api\Public\ProfileController;
use App\Http\Controllers\Api\Public\SavedScholarshipController;
use App\Http\Controllers\Api\Public\ScholarshipController;
use App\Http\Controllers\Api\Public\SearchController;
use App\Http\Controllers\Api\Public\ServiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth — seules routes non-GET accessibles sans connexion
|--------------------------------------------------------------------------
*/
Route::post('/register', [RegisterController::class, 'store']);
Route::post('/login', [LoginController::class, 'store']);
Route::post('/logout', [LogoutController::class, 'destroy'])->middleware('auth:sanctum');

Route::post('/password/request-reset', [PasswordResetController::class, 'requestReset'])
    ->middleware('otp.throttle');
Route::post('/password/reset', [PasswordResetController::class, 'resetPassword']);

/*
|--------------------------------------------------------------------------
| Lecture publique — uniquement des GET, aucune connexion requise
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index']); // public, mais si token -> user inclus
Route::get('/scholarships', [ScholarshipController::class, 'index']);
Route::get('/scholarships/featured', [ScholarshipController::class, 'featured']);
Route::get('/scholarships/{slug}', [ScholarshipController::class, 'show']);

Route::get('/search', [SearchController::class, 'index']);

// ── Posts (lecture publique) ──
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{slug}', [PostController::class, 'show']);

// ── Commentaires by Post (public GET, auth POST/DELETE) ──
Route::get('/posts/{postId}/comments', [CommentController::class, 'indexByPost']);

// ── Likes by Post (auth POST) ──
Route::post('/posts/{postId}/like', [LikeController::class, 'togglePost']);

Route::get('/partners', [PartnerController::class, 'index']);

Route::get('/services', [ServiceController::class, 'index']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

// Lecture des commentaires générale : GET, donc public — seuls store/destroy
// exigent une connexion (cf. groupe auth:sanctum ci-dessous).
Route::get('/comments', [CommentController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Actions — connexion requise (réactions : likes, commentaires,
| favoris, profil)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    // ── Commentaires (création générale) ──
    Route::post('/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);

    // ── Commentaires by Post (création spécifique au post) ──
    Route::post('/posts/{postId}/comments', [CommentController::class, 'storeByPost']);

    // ── Likes (ancien endpoint générique) ──
    Route::post('/likes', [LikeController::class, 'toggle']);

    Route::get('/me/saved-scholarships', [SavedScholarshipController::class, 'index']);
    Route::post('/me/saved-scholarships/{scholarship}', [SavedScholarshipController::class, 'toggle']);

    Route::get('/me/profile', [ProfileController::class, 'show']);
    Route::put('/me/profile', [ProfileController::class, 'update']);
});