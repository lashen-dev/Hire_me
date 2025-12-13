<?php

namespace App\Http\Controllers;
use App\Http\Requests\ApplicantProfileRequest;
use App\Http\Requests\ApplicantRequest;
use App\Http\Requests\CompanyProfileRequest;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use HttpResponses;
    public function profile(Request $request)
    {
        // Return the authenticated user's profile
        return $this->success($request->user(), 'User profile retrieved successfully', 200);
    }

    
}
