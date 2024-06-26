<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HealthEducationMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        $employee = auth('sanctum')->user();
        if(!$employee ||   ! $employee->isA('health-education') || !$employee->active){
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return $next($request);
    }
}
