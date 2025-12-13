<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsProfileCompelet
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Check if the user has a complete profile
        if (!$user || $user->isInCompeleteProfile()) {
            return response()->json([
                'message' => 'Profile is incomplete. Please complete your profile first.'
            ], 403);
        }

        return $next($request);
    }

}
