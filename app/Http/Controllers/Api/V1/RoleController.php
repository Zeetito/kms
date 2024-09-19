<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function users(Request $request,Role $role){
        $users = $role->users($request);
        return response()->json($users);
    }

    // Index
    public function index()
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    // Show
    public function show(Role $role){
        return response()->json($role);
    }

    // Store
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:roles,slug',
            'level' => 'required|integer',
            'subject_type' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $role = new Role();
            $role->name = $request->name;
            $role->slug = $request->slug;
            $role->level = $request->level;
            $role->subject_type = $request->subject_type;
            $role->save();

            return response()->json(['message' => 'Role created successfully!', 'data' => $role], 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create role: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $role = Role::findOrFail($id);
            
            $role->update($request->all());
            
            return response()->json(['message' => 'Role updated successfully!', 'data' => $role]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update role: ' . $e->getMessage()], 404);
        }
    }


}

