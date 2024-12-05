<?php

use App\Models\Role;
use App\Models\User;
use App\Models\Meeting;
use App\Models\Program;
use Illuminate\Http\Request;
use App\Models\AttendanceUser;
use Illuminate\Support\Facades\Route;
use App\Jobs\UserRegisteredNotificationJob;
use App\Notifications\UserRegisteredNotification;
use App\Http\Controllers\Auth\SocialiteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    // return view('welcome');
});


Route::get('auth/google', [SocialiteController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);





// USER ACCOUNT COMPONENTS
Route::get('/user/account_components/phone/{user}', function () {
    return view('user.account.components.phone');
})->name('update_contacts');

// Hello route
Route::get('/hello', function (Request $request) {
    // return AttendanceUser::where('user_id',null)->get()->each->delete();
    return Meeting::all();
    return User::find(1)->profile();
    return Role::where('slug','welfare')->first();
    return User::members()->where('is_student','!=',1)->get()->count();
    return User::find(4)->program();
    return User::students()->get();
    return Program::find(10)->users();
    return User::find(1012)->user_residences;
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
