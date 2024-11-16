<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
            'data' => $users,
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
            'data' => $user,
        ], 200);
    }
}
