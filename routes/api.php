<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\V1\UserController;
use App\Http\Controllers\Api\V1\LoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// USER
    // Get all users
    Route::get('/users', [UserController::class, 'index'])
    ->middleware('auth:sanctum')
    ;

    // NON MEMBERS
        // Get all alumni
        Route::middleware('auth:sanctum')->get('/alumni', function () {
            $users = User::members()->orderByDesc('status')->get();
            return response()->json($users);
        });
    // MEMBERS
        // Get all members
        Route::middleware('auth:sanctum')->get('/members', function () {
            $users = User::members()->orderByDesc('status')->get();
            return response()->json($users);
        });
        // Get affliliate members
        Route::middleware('auth:sanctum')->get('/affiliate_members', function () {
            $users = User::affiliate_members()->orderByDesc('status')->get();
            return response()->json($users);
        });

        // Get non affiliate members
        Route::middleware('auth:sanctum')->get('/non_affiliate_members', function () {
            $users = User::non_affiliate_members()->orderByDesc('status')->get();
            return response()->json($users);
        });

        // Get alumni members
        Route::middleware('auth:sanctum')->get('/alumni_members', function () {
            $users = User::alumni_members()->orderByDesc('status')->get();
            return response()->json($users);
        });

        // Workers Members
        Route::middleware('auth:sanctum')->get('/workers_members', function () {
            $users = User::workers_members()->orderByDesc('status')->get();
            return response()->json($users);
        });

    
    // STUDENTS
        // Get all students
        Route::get('/students', [UserController::class, 'students'])
        ->middleware('auth:sanctum')
        ;

        // Get all affiliate students
        Route::middleware('auth:sanctum')->get('/affiliate_students', function () {
            $users = User::affiliate_students()->orderByDesc('status')->get();
            return response()->json($users);
        });

        // Get non affiliate students
        Route::middleware('auth:sanctum')->get('/non_affiliate_students', function () {
            $users = User::non_affiliate_students()->orderByDesc('status')->get();
            return response()->json($users);
        });

// AUTH
    // Login
    Route::post('/login', [LoginController::class, 'login'])
    ->name('login')
    ;

    // Logout
    Route::middleware('auth:sanctum')->post('/logout', [LoginController::class, 'logout']);


// hello route
Route::get('/hello', function () {
    return 'hello';
});