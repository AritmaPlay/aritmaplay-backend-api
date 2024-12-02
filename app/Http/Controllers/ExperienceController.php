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
            $user = User::find($quiz->user_id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.',
                    'response_code' => 404,
                    'data' => [],
                ], 404);
            }

            $user->totalExp = $user->totalExp + $quiz->exp_received;

            $baseExp = 100; 
            $multiplier = 1.5; 
            $currentLevel = 1; 
            $expForNextLevel = $baseExp; 

            while ($user->totalExp >= $expForNextLevel) {
                $currentLevel++;
                $expForNextLevel += $baseExp * pow($multiplier, $currentLevel - 1);
            }

            $user->level = $currentLevel;

            $user->save();

            $leaderboardEntryController = new LeaderboardEntryController();
            $leaderboardEntryController->addExpToLeaderboardEntry($quiz->exp_received);
            
            return response()->json([
                'success' => true,
                'message' => 'User experience updated successfully.',
                'response_code' => 200,
                'data' => [$user],
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
