<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    use HttpResponses;
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|string|size:6',
        ]);
        $otpRecord = Otp::where('email', $request->email)
            ->where('code', $request->code)
            ->first();

        if (!$otpRecord) {
            return $this->error(null, 'Invalid OTP code.', 401);
        }

        if ($otpRecord->expires_at < now()) {
            $otpRecord->delete();
            return $this->error(null, 'OTP expired.', 401);
        }
        $otpRecord->delete();

        $user = User::where('email', $request->email)->first();
        $user->forceFill([
    'email_verified_at' => now()
])->save();

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success([
            'user' => $user,
            'token' => $token,
        ], 'Login successful', 200);
    }
}
