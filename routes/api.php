<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Quiz\QuizController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Leaderboard\LeaderboardController;

Route::get('/user', [UserController::class, 'index'])->middleware('auth:sanctum');
Route::get('/user/{id}', [UserController::class, 'show'])->middleware('auth:sanctum');


Route::get('/' , function (){
    return response()->json([
        'success' => false,
        'response_code' => 401,
        'message' => 'Unauthenticated. Token not provided or invalid.',
        'data' => []
    ], 401);
})->name('login');

//auth
Route::post('/register', RegisterController::class);
Route::post('/login', LoginController::class);
Route::post('/logout', LogoutController::class)->middleware('auth:sanctum');

//quiz
Route::get('/quiz', [QuizController::class, 'index'])->middleware('auth:sanctum');
Route::get('/quiz/{id}', [QuizController::class, 'show'])->middleware('auth:sanctum');
Route::post('/quiz', [QuizController::class, 'store'])->middleware('auth:sanctum');

//leaderboard
Route::get('/leaderboard', [LeaderboardController::class, 'index'])->middleware('auth:sanctum');
Route::get('/leaderboard/{id}', [LeaderboardController::class, 'show'])->middleware('auth:sanctum');
Route::post('/leaderboard', [LeaderboardController::class, 'store'])->middleware('auth:sanctum');