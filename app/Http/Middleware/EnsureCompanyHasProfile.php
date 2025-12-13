<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Company;

class EnsureCompanyHasProfile
{
    public function handle(Request $request, Closure $next): Response
    {
        $companyId = $request->route('company');

        $company = Company::find($companyId);

        if (!$company) {
            return response()->json(['message' => 'Company not found.'], 404);
        }

        // Check if the company has a profile
        if (!$company->profile) {
            return response()->json([
                'message' => 'Profile not found. Please create a profile first.',
                'redirect_url' => url("companies/{$company->id}/profile/create")


            ], 403);
        }

        return $next($request);
    }
}
