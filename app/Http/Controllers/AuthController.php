<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use \App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\VerifyEmail;

class AuthController extends Controller
{
    use HttpResponses;

    /**
     * @param LoginUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginUserRequest $request)
    {
        $request->validated($request->only(['email', 'password']));

        if(!Auth::attempt($request->only(['email', 'password']))) {
            return $this->error('', 'Credentials do not match', 401);
        }

        $user = User::where('email', $request->email)->first();

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }

    /**
     * @param StoreUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'pending',
            'email_verified_at' => null,
        ]);

        // Generate OTP
        $otp = rand(100000, 999999); // 6-digit OTP
        $user->otp_code = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(10); // OTP valid for 10 mins
        $user->save();

        // Send OTP via email
        Mail::send('emails.otp', ['otp' => $otp], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Your Email Verification OTP');
        });

        return response()->json(['message' => 'Registration successful. OTP sent to email.']);
    }


    public function verifyOtp(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'otp' => 'required|numeric',
    ]);

    $user = User::where('email', $request->email)
                ->where('otp_code', $request->otp)
                ->where('otp_expires_at', '>=', Carbon::now())
                ->first();

    if (!$user) {
        return response()->json(['message' => 'Invalid or expired OTP.'], 400);
    }

    // Mark email as verified
    $user->email_verified_at = now();
    $user->status = 'active';
    $user->otp_code = null; // Clear OTP
    $user->otp_expires_at = null;
    $user->save();

    return response()->json(['message' => 'Email verified successfully.']);
}

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return $this->success([
            'message' => 'You have succesfully been logged out and your token has been removed'
        ]);
    }
}