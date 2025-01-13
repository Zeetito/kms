<?php

namespace App\Http\Controllers\Auth;

use Log;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
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
            'email' => $request->only('email'),
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
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);
    
        // Attempt to reset the user's password
        $response = $this->broker()->reset(
            $this->credentials($request),
            function ($user, $password) {
                $this->resetPassword($user, $password); // Pass both arguments
            }
        );
    
        return $response == \Password::PASSWORD_RESET
            ? $this->sendResetResponse($request, $response)
            : $this->sendResetFailedResponse($request, $response);
    }
    
}
