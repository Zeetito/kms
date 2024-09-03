<?php

use App\Models\Role;
use App\Models\User;
use App\Models\Residence;
use Illuminate\Http\Request;
use App\Models\UserResidence;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\RoleController;
use App\Http\Controllers\api\V1\UserController;
use App\Http\Controllers\Api\V1\LoginController;
use App\Http\Controllers\Api\V1\ResidenceController;
use App\Http\Controllers\Api\V1\UserResidenceController;

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

// ROLES
    // Get all roles
    Route::middleware('auth:sanctum')->get('/roles', function () {
        $roles = Role::all();
        return response()->json($roles);
    });

    // Get Users for a particular Role
    Route::get('/role/{role}/users', [RoleController::class, 'users'])
    ->middleware('auth:sanctum')
    ;

    // Get all ministry members
    Route::middleware('auth:sanctum')->get('/roles/ministry_members', function () {
        $users = User::ministry_members();
        return response()->json($users);
    });

// RESIDENCE
    // Get residences
    Route::middleware('auth:sanctum')->get('/residences', function () {
        $residences = Residence::with('zone')->orderByDesc('name')->get();
        return response()->json($residences);
    });

    // Store Residence
    Route::post('/residence', [ResidenceController::class, 'store'])
    ->middleware('auth:sanctum')
    ;

    // Update Residence
    Route::put('/residence/{residence}', [ResidenceController::class, 'update'])
    ->middleware('auth:sanctum')
    ;

    // Delete Residence
    Route::delete('/residence/{residence}', [ResidenceController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ;
    
// USERRESIDENCE
    // Get all userResidence for the academic year
    Route::middleware('auth:sanctum')->get('/user-residences/{academic_year_id}', function ($academic_year_id) {
        $userResidences = UserResidence::where('academic_year_id', $academic_year_id)
            ->with('user', 'residence')
            ->get();

        return response()->json($userResidences);
    });

    // Store UserResidence
    Route::post('/user-residence', [UserResidenceController::class, 'store'])
    ->middleware('auth:sanctum')
    ;

    // Update UserResidence
    Route::put('/user-residence/{user_residence}', [UserResidenceController::class, 'update'])
    ->middleware('auth:sanctum')
    ;

    // Delete UserResidence
    Route::delete('/user-residence/{user_residence}', [UserResidenceController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ;

// USER
    // GET USER
        // Get all users
        Route::get('/users', [UserController::class, 'index'])
        ->middleware('auth:sanctum')
        ;
    
        // Get a user
        Route::get('/user/{user}', [UserController::class, 'show'])
        ->middleware('auth:sanctum')
        ;

        // Get unverified users
        Route::middleware('auth:sanctum')->get('/unverified_users', function () {
            $users = User::unverified()->get();
            return response()->json($users);
        });

        // Get deactivated users
        Route::middleware('auth:sanctum')->get('/deactivated_users', function () {
            $users = User::deactivated()->get();
            return response()->json($users);
        });

        // Get all active users
        Route::middleware('auth:sanctum')->get('/active_users', function () {
            $users = User::active()->get();
            return response()->json($users);
        });

        // Get all inactive users
        Route::middleware('auth:sanctum')->get('/inactive_users', function () {
            $users = User::inactive()->get();
            return response()->json($users);
        });


        // NON MEMBERS
            // Get all alumni
            Route::middleware('auth:sanctum')->get('/alumni', function () {
                $users = User::members()->get();
                return response()->json($users);
            });
        // MEMBERS
            // Get all members
            Route::middleware('auth:sanctum')->get('/members', function () {
                $users = User::members()->get();
                return response()->json($users);
            });
            // Get affliliate members
            Route::middleware('auth:sanctum')->get('/affiliate_members', function () {
                $users = User::affiliate_members()->get();
                return response()->json($users);
            });

            // Get non affiliate members
            Route::middleware('auth:sanctum')->get('/non_affiliate_members', function () {
                $users = User::non_affiliate_members()->get();
                return response()->json($users);
            });

            // Get alumni members
            Route::middleware('auth:sanctum')->get('/alumni_members', function () {
                $users = User::alumni_members()->get();
                return response()->json($users);
            });

            // Workers Members
            Route::middleware('auth:sanctum')->get('/workers_members', function () {
                $users = User::workers_members()->get();
                return response()->json($users);
            });

        
        // STUDENTS
            // Get all students
            Route::middleware('auth:sanctum')->get('/students', function () {
                $users = User::students()->get();
                return response()->json($users);
            });

            // Get all affiliate students
            Route::middleware('auth:sanctum')->get('/affiliate_students', function () {
                $users = User::affiliate_students()->get();
                return response()->json($users);
            });

            // Get non affiliate students
            Route::middleware('auth:sanctum')->get('/non_affiliate_students', function () {
                $users = User::non_affiliate_students()->get();
                return response()->json($users);
            });

    // POST USER
        // Register
        Route::post('/register', [UserController::class, 'register'])
        ->middleware('auth:sanctum')
        ;

        // Update
        Route::post('/update_user/{user}', [UserController::class, 'update'])
        ->middleware('auth:sanctum')
        ;

        // Update User Account Status
        Route::put('/user_account/{user}', [UserController::class, 'user_account'])
        ->middleware('auth:sanctum')
        ;



        // AUTH
    // Login
    Route::post('/login', [LoginController::class, 'login'])
    ->name('login')
    ;

    // Logout
    Route::middleware('auth:sanctum')->post('/logout', [LoginController::class, 'logout']);


// hello route
Route::get('/hello', function () {
    // return getAcademicYearId();
    return App\Models\User::find(1)->residence();
    return 'hello';
});