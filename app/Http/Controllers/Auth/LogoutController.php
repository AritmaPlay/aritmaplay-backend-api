<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'response_code' => 401,
                'message' => 'User not authenticated.',
                'data' => []
            ], 401);
        }

        if ($user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
            return response()->json([
                'success' => true,
                'response_code' => 200,
                'message' => 'Successfully logged out.',
                'data' => []
            ],200);
        }

        return response()->json([
            'success' => false,
            'response_code' => 400,
            'message' => 'Token not found.',
            'data' => []
        ], 400); 
    }
}
