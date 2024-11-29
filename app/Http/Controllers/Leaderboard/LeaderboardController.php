<?php

namespace App\Http\Controllers\Leaderboard;

use Exception;
use App\Models\Leaderboard;
use Illuminate\Http\Request;
use App\Models\LeaderboardEntry;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Termwind\Components\Dd;

class LeaderboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function store(Request $request)
    {
        $rules = [
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

        $leaderboard = Leaderboard::create([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Leaderboard created successfully',
            'response_code' => 200,
            'data' => [$leaderboard],
        ], 200);
    }

    public function index()
    {
        $leaderboards = Leaderboard::all();

        return response()->json([
            'success' => true,
            'message' => 'Leaderboards retrieved successfully.',
            'response_code' => 200,
            'data' => [$leaderboards],
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
            'message' => 'Leaderboard retrieved successfully.',
            'response_code' => 200,
            'data' => [$leaderboard],
        ], 200);
    }

    public function update(Request $request, $id)
    {
    try {
        $leaderboard = Leaderboard::find($id);
    
        if (!$leaderboard) {
            return response()->json([
                'success' => false,
                'message' => 'Leaderboard not found.',
                'response_code' => 404,
                'data' => [],
            ], 404);
        }

        $validatedData = $request->validate([
            'status' => 'nullable|string|in:active,inactive',
        ]);
        
        $leaderboard->status = $validatedData['status'];
        $leaderboard->save();

        return response()->json([
            'success' => true,
            'message' => 'Leaderboard updated successfully.',
            'response_code' => 200,
            'data' => [$leaderboard],
        ], 200);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Leaderboard updated failed.',
            'response_code' => 404,
            'data' => $e->getMessage(),
        ], 404);
    }
    }

    public function showActiveLeaderboard()
    {
        
        //1. Ambil id leaderboard minggu ini (yang aktif)
        $leaderboard = Leaderboard::where('status', 'active')->get()->first();
        //2. Cari leaderboard entry dengan leaderboard tersebut
        $entries = LeaderboardEntry::where('leaderboard_id', $leaderboard->leaderboard_id)->orderBy('totalExpPerWeek', 'desc')->limit(10)->get();
        
        if (!$leaderboard) {
            return response()->json([
                'success' => false,
                'message' => 'Leaderboard not found.',
                'response_code' => 404,
                'data' => [],
            ], 404);
        }
        if (!$entries) {
            return response()->json([
                'success' => false,
                'message' => 'Leaderboard entry not found.',
                'response_code' => 404,
                'data' => [],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Leaderboard retrieved successfully.',
            'response_code' => 200,
            'data' => ['leaderboard'=>$leaderboard, 'entries'=>$entries],
        ], 200);
    }

    public function createLeaderboardWeek()
    {
        $leaderboard = Leaderboard::create([
            'name' => 'Leaderboard Mingguan',
            'start_date' => now(),
            'end_date' => now()->addWeek(),
            'status' => 'active',
        ]);
    }

    public function endLeaderboardWeek()
    {
        $leaderboard = Leaderboard::where('status', 'active')->first();
        $leaderboard->status = 'inactive';
        $leaderboard->save();
    }
}
