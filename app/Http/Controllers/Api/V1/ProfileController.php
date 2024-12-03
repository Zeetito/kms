<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
 
    // Update Use profile
    public function update(Request $request, User $user){

        return $request;

        return response()->json(['message' => 'This route is a work in progress'], 200);
    }
}
