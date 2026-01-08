<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Pest\Support\Str;

class SocialController extends Controller
{
    use HttpResponses;

    public function redirect(string $driver)
    {
        if (!in_array($driver, ['facebook', 'google'])) {
            return $this->error(null, 'InValid Driver', 403);
        }
        return Socialite::driver($driver)->redirect();
    }

    public function callback(string $driver)
    {
        try {

            if (!in_array($driver, ['facebook', 'google'])) {
                return $this->error(null, 'InValid Driver', 403);
            }
            $socialUser = Socialite::driver($driver)->stateless()->user();

            $user = User::where('email', $socialUser->getEmail())->first();

            if ($user) {
                $user->update([
                    'email_verified_at' => now(),
                ]);
            } else {
                $user = User::create([
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'password' => Hash::make(Str::random(10)),
                    'role' => 'applicant',
                    'email_verified_at' => now(),
                ]);
            }

            $token = $user->createToken('google-token')->plainTextToken;


            return response()->json([
                'status' => 'success',
                'message' => 'User logged in successfully',
                'user' => $user,
                'token' => $token
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Login failed: ' . $e->getMessage()], 500);
        }
    }
}
