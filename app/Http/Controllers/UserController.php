<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Http\Request;
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

        $quizTambahSuccesRate = Quiz::select('user_id')
        ->selectRaw('SUM(correct_question) as total')
        ->selectRaw('COUNT(*) as total_quiz')
        ->where('quiz_mode', 'tambah')
        ->where('user_id', $user->user_id)
        ->groupBy('user_id')
        ->get();
        $quizTambahSuccesRate = $this->checkQuizRateEmpty($quizTambahSuccesRate);

        $quizKurangSuccesRate = Quiz::select('user_id')
        ->selectRaw('SUM(correct_question) as total')
        ->selectRaw('COUNT(*) as total_quiz')
        ->where('quiz_mode', 'kurang')
        ->where('user_id', $user->user_id)
        ->groupBy('user_id')
        ->get();
        $quizKurangSuccesRate = $this->checkQuizRateEmpty($quizKurangSuccesRate);

        $quizKaliSuccesRate = Quiz::select('user_id')
        ->selectRaw('SUM(correct_question) as total')
        ->selectRaw('COUNT(*) as total_quiz')
        ->where('quiz_mode', 'kali')
        ->where('user_id', $user->user_id)
        ->groupBy('user_id')
        ->get();
        $quizKaliSuccesRate = $this->checkQuizRateEmpty($quizKaliSuccesRate);

        $quizBagiSuccesRate = Quiz::select('user_id')
        ->selectRaw('SUM(correct_question) as total')
        ->selectRaw('COUNT(*) as total_quiz')
        ->where('quiz_mode', 'bagi')
        ->where('user_id', $user->user_id)
        ->groupBy('user_id')
        ->get();
        $quizBagiSuccesRate = $this->checkQuizRateEmpty($quizBagiSuccesRate);

        settype($quizTambahSuccesRate, 'integer');
        settype($quizKurangSuccesRate, 'integer');
        settype($quizKaliSuccesRate, 'integer');
        settype($quizBagiSuccesRate, 'integer');

        return  ['quiz_done'=> $quizDone,
                'quiz_tambah_success_rate' => $quizTambahSuccesRate,
                'quiz_kurang_success_rate' => $quizKurangSuccesRate,
                'quiz_kali_success_rate' => $quizKaliSuccesRate,
                'quiz_bagi_success_rate' => $quizBagiSuccesRate];

        
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
