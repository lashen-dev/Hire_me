<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\MessageResource;
use App\Models\Admin;
use App\Models\Applicant;
use App\Models\Company;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    use HttpResponses;
    public function register(RegisterRequest $request)
    {
        // Validate the request
        $validated = $request->validated();

        $user = User::create($validated);


        // Check if the user is a company
        if ($validated['role'] === 'company') {
            // Create a new company for the user
            $user->assignRole('company');
            $company = Company::create([
                'user_id' => $user->id,
                'name' => $user->name,

            ]);

            return $this->success($company, 'Company registered successfully', 201);
        } else {
            // Create a new applicant for the user
            $user->assignRole('applicant');
            $applicant = Applicant::create([
                'user_id' => $user->id,
                'name' => $user->name,
            ]);

            return $this->success($applicant, 'Applicant registered successfully', 201);
        }
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return $this->error(null, 'Invalid credentials', 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 'Login successful', 200);
    }


    public function logout(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return $this->error(null, 'No authenticated user found', 401);
        }

        $user->currentAccessToken()->delete();

        return $this->success(null, 'Logout successful', 200);
    }
}
