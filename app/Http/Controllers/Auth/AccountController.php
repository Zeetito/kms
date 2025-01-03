<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    // Activate account
    public function activate_account($email){
        $user = User::where('email' , $email)->first();
        abort_unless($user , 404);
        // activate account
        if($user){
          $user->is_active = true;
          $user->save();
            return redirect()->route('home')->with('status' , 'Account Activated');
        }
        // return redirect()->route('login')->with('status' , 'Invalid Token');

    }
}
