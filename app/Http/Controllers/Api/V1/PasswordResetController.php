<?php

namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class PasswordResetController extends Controller
{
    public function forgotPassword(Request $request) {
        $request->validate(['email' => 'required|email']);
    
        // Check if user exists
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'Email not found'], 404);
        }
    
        // Generate a token
        $token = Str::random(64);
    
        // Store in password_resets table (manually create this table)
        DB::table('password_resets')->updateOrInsert(
            ['email' => $user->email],
            ['token' => $token, 'created_at' => Carbon::now()]
        );
    
        // Send reset email
        $resetLink = url("/password/reset?token=$token&email=" . urlencode($user->email));
        Mail::raw("Click here to reset your password: $resetLink", function ($message) use ($user) {
            $message->to($user->email)->subject('Reset Password');
        });
    
        return response()->json(['message' => 'Reset link sent to your email']);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->save();

                $user->setRememberToken(Str::random(60));

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response()->json(['status' => __($status)], 200);
        }

        return response()->json(['email' => [__($status)]], 400);
    }
}
