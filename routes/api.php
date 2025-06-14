<?php

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
});