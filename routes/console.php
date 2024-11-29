<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Http\Controllers\Leaderboard\LeaderboardController;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('test', function () {
    $this->comment('test');
})->purpose('Display an inspiring quote')->everyFiveSeconds();


Schedule::call(function () {
    app(LeaderboardController::class)->endLeaderboardWeek();
    //panggil fungsi CreateLeaderboardWeek pada leaderboardcontroller
    app(LeaderboardController::class)->createLeaderboardWeek();
})->everyMinute();