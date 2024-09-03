<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function users(Request $request,Role $role){
        $users = $role->users($request);
        return response()->json($users);
    }
}
