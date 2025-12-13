<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsApplicant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and has an applicant profile
        if (!$request->user() || !$request->user()->role || !$request->user()->isApplicant()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Proceed to the next middleware or controller
        return $next($request);
    }
}
