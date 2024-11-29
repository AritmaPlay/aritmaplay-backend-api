<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Leaderboard\LeaderboardEntryController;

class ExperienceController extends Controller
{
    public function updateUserExperience($quiz)
    {
        try {
            // Temukan user berdasarkan user_id pada quiz
            $user = User::find($quiz->user_id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.',
                    'response_code' => 404,
                    'data' => [],
                ], 404);
            }

            // Tambahkan exp_received ke totalExp user
            $user->totalExp = $user->totalExp + $quiz->exp_received;

            // Hitung level berdasarkan exp eksponensial
            $baseExp = 100; // Exp dasar untuk naik dari Level 1 ke Level 2
            $multiplier = 1.5; // Faktor pengali eksponensial
            $currentLevel = 1; // Mulai dari Level 1
            $expForNextLevel = $baseExp; // Exp yang diperlukan untuk naik level berikutnya

            // Hitung level user berdasarkan totalExp
            while ($user->totalExp >= $expForNextLevel) {
                $currentLevel++;
                $expForNextLevel += $baseExp * pow($multiplier, $currentLevel - 1);
            }

            $user->level = $currentLevel;

            // Simpan perubahan pada user
            $user->save();


            // Panggil fungsi test pada leaderboardEntryController
            $leaderboardEntryController = new LeaderboardEntryController();
            $leaderboardEntryController->addExpToLeaderboardEntry($quiz->exp_received);
            
            return response()->json([
                'success' => true,
                'message' => 'User experience updated successfully.',
                'response_code' => 200,
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user experience.',
                'response_code' => 500,
                'data' => $e->getMessage(),
            ], 500);
        }
    }
}
