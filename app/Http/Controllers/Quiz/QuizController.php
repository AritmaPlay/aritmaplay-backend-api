<?php

namespace App\Http\Controllers\Quiz;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ExperienceController;

class QuizController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function store(Request $request)
    {
        $rules =[
            'quiz_mode' => 'required|string',
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
        $quiz = Quiz::create([
            'quiz_mode' => $request->quiz_mode,
            'exp_received' => $request->correct_question * 10,
            'total_question' => $request->total_question,
            'quiz_time' => $request->quiz_time,
            'correct_question' => $request->correct_question,
            'user_id' => $request->user_id
        ]);

        $experienceController = new ExperienceController();
        $experienceResponse = $experienceController->updateUserExperience($quiz);
    
        return response()->json([
            'success' => true,
            'message' => 'Quiz created successfully and user experience updated.',
            'response_code' => 200,
            'data' => [
                'quiz' => [$quiz],
                'user_update' => [json_decode($experienceResponse->getContent(), true)]
            ],
        ], 200);
    }

    public function index()
    {
        $quizzes = Quiz::all();

        return response()->json([
            'success' => true,
            'message' => 'Quizzes retrieved successfully.',
            'response_code' => 200,
            'data' =>[$quizzes],
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

    public function showByUserId($id)
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

        $quizzes = Quiz::where('user_id', $id)
        ->orderBy('created_at', 'desc')
        ->get();


        if ($quizzes->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No quizzes found for the given user ID.',
                'response_code' => 404,
                'data' => [],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Quizzes retrieved successfully.',
            'response_code' => 200,
            'data' => $quizzes,
        ], 200);
    
    }


}
