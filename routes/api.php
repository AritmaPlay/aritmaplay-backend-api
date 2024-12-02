<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Quiz\QuizController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Leaderboard\LeaderboardController;
use App\Http\Controllers\Leaderboard\LeaderboardEntryController;
use App\Http\Middleware\VerifyCloudSchedulerToken;

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

//user
Route::resources(['user' => UserController::class], ['middleware' => 'auth:sanctum']);

//quiz
Route::resource('/quiz', QuizController::class)->middleware('auth:sanctum');
Route::get('/quiz/user/{id}', [QuizController::class, 'showByUserId'])->middleware('auth:sanctum');

//leaderboard
Route::resource('/leaderboard', LeaderboardController::class)->middleware('auth:sanctum');
Route::get('/leaderboard-active', [LeaderboardController::class, 'showActiveLeaderboard'])->middleware('auth:sanctum');

//leaderboardEntry
Route::resource('/leaderboard-entry', LeaderboardEntryController::class)->middleware('auth:sanctum');

Route::post('/run-scheduled-task', function () {
    // Logika atau fungsi yang dijalankan
    app(LeaderboardController::class)->endLeaderboardWeek();
    //panggil fungsi CreateLeaderboardWeek pada leaderboardcontroller
    app(LeaderboardController::class)->createLeaderboardWeek();
    Log::info('Scheduled task executed successfully.');

    return response()->json(['message' => 'Scheduled task executed successfully.']);
})->middleware(VerifyCloudSchedulerToken::class); // Middleware opsional untuk keamanan

