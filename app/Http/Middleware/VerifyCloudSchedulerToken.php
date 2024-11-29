<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyCloudSchedulerToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('X-Scheduler-Token');

        if ($token !== env('CLOUD_SCHEDULER_TOKEN')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}