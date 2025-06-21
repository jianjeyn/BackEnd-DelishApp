<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TrendingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/


// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/register', [UserController::class, 'register']);

// home
Route::get('/home', [HomeController::class, 'index']);

// search
Route::get('/search', [SearchController::class, 'search-page']);

// recipes
Route::get('/recipes', [RecipeController::class, 'index']);
Route::get('/recipes/{id}', [RecipeController::class, 'show']);
Route::post('/recipes', [RecipeController::class, 'store']);
Route::put('/recipes/{id}', [RecipeController::class, 'update']);
Route::delete('/recipes/{id}', [RecipeController::class, 'destroy']);

// reviews
Route::post('/recipes/{recipeId}/reviews', [ReviewController::class, 'store']);
Route::get('/recipes/{recipeId}/reviews', [ReviewController::class, 'index']);

// communities
Route::get('/communities', [CommunityController::class, 'index']);
Route::get('/communities/{id}', [CommunityController::class, 'show']);
Route::post('/communities/{id}/remove-user', [CommunityController::class, 'removeUser']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // communities
    Route::post('/communities/{id}/add-user', [CommunityController::class, 'addUser']);
    Route::post('/communities/{id}/remove-user', [CommunityController::class, 'removeUser']);

    // User profile
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::get('/profile/following', [ProfileController::class, 'following']);

    // notifications to user
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);

    //user profile
    // Endpoint untuk menampilkan profil dan statistik pengguna
    Route::get('/profile', [ProfileController::class, 'index']);
    // Endpoint untuk memperbarui profil
    Route::put('/profile', [ProfileController::class, 'update']);
    // Endpoint untuk menampilkan daftar followers
    Route::get('/profile/followers', [ProfileController::class, 'followers']);
    // Endpoint untuk menampilkan daftar following
    Route::get('/profile/following', [ProfileController::class, 'following']);
    // Endpoint untuk follow user lain
    Route::post('/profile/follow/{userId}', [ProfileController::class, 'followUser']);
    // Endpoint untuk share data profil
    Route::get('/profile/share', [ProfileController::class, 'shareProfile']);
    // Aktifkan jika fitur ini digunakan
    // Route::patch('/profile/notifications/{userId}', [ProfileController::class, 'manageNotifications']);
    // Route::patch('/profile/block/{userId}', [ProfileController::class, 'blockUser']);
    // Route::patch('/profile/unblock/{userId}', [ProfileController::class, 'unblockUser']);
    // Route::post('/profile/report/{userId}', [ProfileController::class, 'reportUser']);

    // API endpoint untuk mencari followers dan following berdasarkan username & search
    Route::get('/profile/{username}/followers/search', [ProfileController::class, 'apiSearchFollowers']);
    Route::get('/profile/{username}/following/search', [ProfileController::class, 'apiSearchFollowing']);
});

// trending recipes
Route::get('/trending', [TrendingController::class, 'index']);
Route::get('/trending/{id}', [TrendingController::class, 'show']);