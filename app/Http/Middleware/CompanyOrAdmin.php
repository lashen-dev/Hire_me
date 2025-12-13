<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyOrAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && ($user->role === 'company' || $user->role === 'admin')) {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}
