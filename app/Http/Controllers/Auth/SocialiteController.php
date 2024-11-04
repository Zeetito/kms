<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Socialite;
use App\Models\User;
use Auth;
use Exception;

class SocialiteController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            // Check if the user already exists in the database
            $existingUser = User::where('email', $googleUser->email)->first();
            
            if($existingUser){
                // Log the user in
                Auth::login($existingUser);
            } else {
                // Create a new user in the database
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => encrypt('my-google'), // Not used, just required
                ]);
                
                Auth::login($newUser);
            }
            
            // Redirect the user after login
            return redirect()->intended('home');
        } catch (Exception $e) {
            return redirect('/auth/google');
        }
    }
}
