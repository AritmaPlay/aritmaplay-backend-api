<?php

namespace App\Http\Controllers\Leaderboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Leaderboard;
use Illuminate\Support\Facades\Validator;

class LeaderboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function store(Request $request)
    {
        $rules =[
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'nullable|string|in:active,inactive',
        ]; 
    
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation leaderboard failed',
                'response_code' => 400,
                'data' => $validator->errors(),
            ], 400);
        }
    
        $user = Leaderboard::create([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
        ]);
    
        return response()->json([
            'success' => true,
            'message' => 'Leaderboard created successfully',
            'response_code' => 200,
            'data' => $user
        ]);
    }

    public function index()
    {
        $leaderboards = Leaderboard::all();

        return response()->json([
            'success' => true,
            'message' => 'Leaderboards retrieved successfully.',
            'response_code' => 200,
            'data' => $leaderboards,
        ], 200);
    }

    public function show($id)
    {
        $leaderboard = Leaderboard::find($id);

        if (!$leaderboard) {
            return response()->json([
                'success' => false,
                'message' => 'Leaderboard not found.',
                'response_code' => 404,
                'data' => [],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'leaderboard retrieved successfully.',
            'response_code' => 200,
            'data' => $leaderboard,
        ], 200);
    }
}
