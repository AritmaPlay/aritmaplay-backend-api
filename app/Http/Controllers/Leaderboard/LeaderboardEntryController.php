<?php

namespace App\Http\Controllers\Leaderboard;

use Exception;
use App\Models\User;
use App\Models\Leaderboard;
use Illuminate\Http\Request;
use App\Models\LeaderboardEntry;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
            'last_updated' => $request->last_updated,
        ]);
    
        return response()->json([
            'success' => true,
            'message' => 'Leaderboard entry created successfully',
            'response_code' => 200,
            'data' => [$user]
        ]);
    }

    public function index()
    {
        $leaderboardsEntries = LeaderboardEntry::all();

        return response()->json([
            'success' => true,
            'message' => 'Leaderboards entries retrieved successfully.',
            'response_code' => 200,
            'data' => [$leaderboardsEntries],
        ], 200);
    }

    public function show($id)
    {
        $leaderboardEntry = LeaderboardEntry::find($id);

        if (!$leaderboardEntry) {
            return response()->json([
                'success' => false,
                'message' => 'Leaderboard entry not found.',
                'response_code' => 404,
                'data' => [],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Leaderboard entry retrieved successfully.',
            'response_code' => 200,
            'data' => [$leaderboardEntry],
        ], 200);
    }

    public function addExpToLeaderboardEntry($exp_received) {
        $user = Auth::user();
        $leaderboard_id = Leaderboard::where('status', 'active')->first()->leaderboard_id;
        $leaderboardEntry = LeaderboardEntry::where('user_id', $user->user_id)->where('leaderboard_id', $leaderboard_id)->first();
        if ($leaderboardEntry) {
            $leaderboardEntry->totalExpPerWeek = $leaderboardEntry->totalExpPerWeek + $exp_received;
            $leaderboardEntry->save();
        }
        else {
            LeaderboardEntry::create([
                'leaderboard_id' => $leaderboard_id,
                'user_id' => $user->user_id,
                'totalExpPerWeek' => $user->totalExp,
            ]);
    }
}
}