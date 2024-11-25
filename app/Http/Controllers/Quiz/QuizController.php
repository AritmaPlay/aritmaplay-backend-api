<?php

namespace App\Http\Controllers\Quiz;

use App\Models\Quiz;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function store(Request $request)
    {
        $rules =[
            'quiz_mode' => 'required|string',
            'exp_received' => 'required|integer',
            'total_question' => 'required|integer',
            'quiz_time' => 'required|integer',
            'correct_question' => 'required|integer',
            'user_id' => 'required|exists:users,user_id',
        ]; 
    
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation quiz failed',
                'response_code' => 400,
                'data' => $validator->errors(),
            ], 400);
        }
    
        $user = Quiz::create([
            'quiz_mode' => $request->quiz_mode,
            'exp_received' => $request->exp_received,
            'total_question' => $request->total_question,
            'quiz_time' => $request->quiz_time,
            'correct_question' => $request->correct_question,
            'user_id' => $request->user_id
        ]);
    
        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'response_code' => 200,
            'data' => $user
        ]);
    }

    public function index()
    {
        $quizzes = Quiz::all();

        return response()->json([
            'success' => true,
            'message' => 'Quizzes retrieved successfully.',
            'response_code' => 200,
            'data' => $quizzes,
        ], 200);
    }

    public function show($id)
    {
        $quiz = Quiz::find($id);

        if (!$quiz) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz not found.',
                'response_code' => 404,
                'data' => [],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Quiz retrieved successfully.',
            'response_code' => 200,
            'data' => $quiz,
        ], 200);
    }
}
