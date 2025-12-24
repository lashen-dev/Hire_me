<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\MessageResource;
use App\Mail\OtpMail;
use App\Models\Admin;
use App\Models\Applicant;
use App\Models\Company;
use App\Models\Otp;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{

    use HttpResponses;
    public function register(RegisterRequest $request)
    {
        // Validate the request
        $validated = $request->validated();

        $user = User::create($validated);

        $code = rand(111111, 999999);

        Otp::create([
            'email' => $user->email,
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($user->email)->send(new OtpMail($code));


        // Check if the user is a company
        if ($validated['role'] === 'company') {
            // Create a new company for the user
            $user->assignRole('company');
            $company = Company::create([
                'user_id' => $user->id,
                'name' => $user->name,

            ]);

            
        } else {
            // Create a new applicant for the user
            $user->assignRole('applicant');
            $applicant = Applicant::create([
                'user_id' => $user->id,
                'name' => $user->name,
            ]);

            
        }

        return $this->success([
        'email' => $user->email,
        'next_step' => 'verify_otp',
    ], 'Registration successful. Please check your email to verify your account.', 201);
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return $this->error(null, 'Invalid credentials', 401);
        }

        if ($user->email_verified_at === null) {
        return $this->error(null, 'الحساب غير مفعل، يرجى تأكيد الإيميل أولاً', 403);
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
