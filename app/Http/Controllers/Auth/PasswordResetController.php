<?php

namespace App\Http\Controllers\Auth;

use Log;
use Password;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class PasswordResetController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You may override any
    | methods you wish to customize its functionality.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Show the password reset form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return View
     */
    public function showResetForm(Request $request, $token = null)
    {
        Log::info('Reset Form Inputs', [
            'token' => $token,
            'email' => $request->email,
            // 'email' => urldecode($request->email),
        ]);
    
        if (empty($token) || empty($request->email)) {
            abort(400, 'Invalid reset link');
        }
    
        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }
    
    

    /**
     * Handle a password reset request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reset(Request $request) 
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);
    
        // Check if the token and email exist
        $reset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();
    
        if (!$reset) {
            return back()->withErrors(['message' => 'Invalid token or email']);
        }
    
        // Update user's password
        $user = User::where('email', $request->email)->first();
        $user->update(['password' => bcrypt($request->password)]);
    
        // Delete the reset token after successful password reset
        DB::table('password_resets')->where('email', $request->email)->delete();
    
        return redirect('/login')->with('status', 'Password has been reset.');
    }
    
    
}
