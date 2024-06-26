<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ChildDoctorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $employee = auth('sanctum')->user();
        if(!$employee ||   ! $employee->isA('child-doctor') || !$employee->active){
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return $next($request);
    }
}
