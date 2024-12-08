<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Quiz;
use App\Models\User;
use App\Models\Leaderboard;
use Illuminate\Http\Request;
use App\Models\LeaderboardEntry;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display the user.
     */

    public function index()
    {
        $users = User::all();

        return response()->json([
            'success' => true,
            'message' => 'Users retrieved successfully.',
            'response_code' => 200,
            'data' => [$users],
        ], 200);
    }
    public function show($id)
    {   
        $user = User::find($id);
        $leaderboard = Leaderboard::firstWhere('status', 'active');

        $userRank = null;

        $leaderboardEntry = LeaderboardEntry::where('leaderboard_id', $leaderboard->leaderboard_id)
            ->orderBy('totalExpPerWeek', 'desc')
            ->get();

        foreach ($leaderboardEntry as $key => $value) {
            if ($value->user_id == Auth::user()->user_id) {
                $userRank = $key+1;
                break;
            }
        }
        $user->userRank = $userRank;

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
                'response_code' => 404,
                'data' => [],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'User retrieved successfully.',
            'response_code' => 200,
            'data' => ['user' => $user, 
                        'stats' => $this->getStats($user->user_id)],
        ], 200);
    }

    public function update(Request $request, $id)
    {
    try {
        $user = User::find($id);
    
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
                'response_code' => 404,
                'data' => [],
            ], 404);
        }

        $validatedData = $request->validate([
            'level' => 'required|integer',
            'totalExp' => 'required|integer',
        ]);
        
        $user->level = $validatedData['level'];
        $user->totalExp = $validatedData['totalExp'];
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully.',
            'response_code' => 200,
            'data' => [$user],
        ], 200);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'User updated failed.',
            'response_code' => 404,
            'data' => $e->getMessage(),
        ], 404);
    }
    }

    public function getStats($id){
        $user = User::find($id);
        $quizDone = Quiz::where('user_id', $user->user_id)->count();

        $quizPenjumlahanSuccesRate = Quiz::select('user_id')
        ->selectRaw('SUM(correct_question) as total')
        ->selectRaw('COUNT(*) as total_quiz')
        ->where('quiz_mode', 'Penjumlahan')
        ->where('user_id', $user->user_id)
        ->groupBy('user_id')
        ->get();
        $quizPenjumlahanSuccesRate = $this->checkQuizRateEmpty($quizPenjumlahanSuccesRate);

        $quizPenguranganSuccesRate = Quiz::select('user_id')
        ->selectRaw('SUM(correct_question) as total')
        ->selectRaw('COUNT(*) as total_quiz')
        ->where('quiz_mode', 'Pengurangan')
        ->where('user_id', $user->user_id)
        ->groupBy('user_id')
        ->get();
        $quizPenguranganSuccesRate = $this->checkQuizRateEmpty($quizPenguranganSuccesRate);

        $quizPerkalianSuccesRate = Quiz::select('user_id')
        ->selectRaw('SUM(correct_question) as total')
        ->selectRaw('COUNT(*) as total_quiz')
        ->where('quiz_mode', 'Perkalian')
        ->where('user_id', $user->user_id)
        ->groupBy('user_id')
        ->get();
        $quizPerkalianSuccesRate = $this->checkQuizRateEmpty($quizPerkalianSuccesRate);

        $quizPembagianSuccesRate = Quiz::select('user_id')
        ->selectRaw('SUM(correct_question) as total')
        ->selectRaw('COUNT(*) as total_quiz')
        ->where('quiz_mode', 'Pembagian')
        ->where('user_id', $user->user_id)
        ->groupBy('user_id')
        ->get();
        $quizPembagianSuccesRate = $this->checkQuizRateEmpty($quizPembagianSuccesRate);

        settype($quizPenjumlahanSuccesRate, 'integer');
        settype($quizPenguranganSuccesRate, 'integer');
        settype($quizPerkalianSuccesRate, 'integer');
        settype($quizPembagianSuccesRate, 'integer');

        return  ['quiz_done'=> $quizDone,
                'quiz_penjumlahan_success_rate' => $quizPenjumlahanSuccesRate,
                'quiz_pengurangan_success_rate' => $quizPenguranganSuccesRate,
                'quiz_perkalian_success_rate' => $quizPerkalianSuccesRate,
                'quiz_pembagian_success_rate' => $quizPembagianSuccesRate];

        
    }

    public function checkQuizRateEmpty($quizRate) {
        if(!empty($quizRate[0])){
            $quizRate = $quizRate[0]->total  / ($quizRate[0]->total_quiz * 10) * 100 ;
        }
        else{
            $quizRate = 0;
        }

        return $quizRate;
    }
}
