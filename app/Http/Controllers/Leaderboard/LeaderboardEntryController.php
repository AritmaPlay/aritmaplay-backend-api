<?php

namespace App\Http\Controllers\Leaderboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LeaderboardEntry;
use Illuminate\Support\Facades\Validator;

class LeaderboardEntryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function store(Request $request)
    {
        $rules =[
            'leaderboard_id' => 'required|exists:leaderboards,leaderboard_id',
            'user_id' => 'required|exists:users,user_id',
            'totalExpPerWeek' => 'required|integer',
            'rank' => 'required|integer',
            'last_updated' => 'nullable|date',
        ]; 
    
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation leaderboard entry failed',
                'response_code' => 400,
                'data' => $validator->errors(),
            ], 400);
        }
    
        $user = LeaderboardEntry::create([
            'leaderboard_id' => $request->leaderboard_id,
            'user_id' => $request->user_id,
            'totalExpPerWeek' => $request->totalExpPerWeek,
            'rank' => $request->rank,
            'last_updated' => $request->last_updated,
        ]);
    
        return response()->json([
            'success' => true,
            'message' => 'Leaderboard entry created successfully',
            'response_code' => 200,
            'data' => $user
        ]);
    }

    public function index()
    {
        $leaderboards = LeaderboardEntry::all();

        return response()->json([
            'success' => true,
            'message' => 'Leaderboards entries retrieved successfully.',
            'response_code' => 200,
            'data' => $leaderboards,
        ], 200);
    }

    public function show($id)
    {
        $leaderboard = LeaderboardEntry::find($id);

        if (!$leaderboard) {
            return response()->json([
                'success' => false,
                'message' => 'Leaderboard entry not found.',
                'response_code' => 404,
                'data' => [],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'leaderboard entry retrieved successfully.',
            'response_code' => 200,
            'data' => $leaderboard,
        ], 200);
    }
}
