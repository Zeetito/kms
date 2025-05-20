<?php

use App\Models\Role;
use App\Models\User;
use App\Models\Zone;
use App\Models\Meeting;
use App\Models\Program;
use App\Models\Attendance;
use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Models\AttendanceUser;
use Illuminate\Support\Facades\Route;
use App\Jobs\UserRegisteredNotificationJob;
use App\Http\Controllers\Api\V1\ZoneController;
use App\Http\Controllers\Auth\AccountController;
use App\Notifications\UserRegisteredNotification;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Api\V1\TempUserController;
use App\Http\Controllers\Api\V1\AttendanceController;
use App\Http\Controllers\Auth\PasswordResetController;

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

// Add Zone-User
Route::get('/add-zone-user/{zone}', function ($zone) {
    // Zone
    $zone = Zone::find($zone);
    // Active Attendance Session
    $attendance_session = Attendance::active_sessions() ?? null;

    return view('user.zone-based.add-zone-user', compact('zone', 'attendance_session'));
}) ->name('add.zone.user.view');

// Store Zone User
Route::post('/add-zone-user' , [ZoneController::class, 'addZoneUser'])->name('add.zone.user');


// Go to attendance page
Route::get('/active_attendance_session', function () {

    $attendance_session = Attendance::active_sessions() ?? null;
    if($attendance_session == null){
        return redirect()->route('home');
    }

    $users = User::all();

    $zones = Zone::all();

    return view('attendance.attendance-users', compact('attendance_session', 'users', 'zones'));
})->name('active_attendance_session');

// Submit Attendance
Route::post('/submit-attendance', [AttendanceController::class, 'submit_attendance'])->name('submit.attendance');



// TEMP USER
// Create a Temp User
Route::post('/temp-user' , [TempUserController::class, 'store'])->name('add.temp.user');



Route::get('auth/google', [SocialiteController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);


Route::get('password/reset/{token}/{email}', [PasswordResetController::class, 'showResetForm']);
Route::post('password/reset', [PasswordResetController::class, 'reset'])->name('password.update');

Route::get('account/activate/{email}', [
AccountController::class, 'activate_account'])->name('account.activate');



// USER ACCOUNT COMPONENTS
// Route::get('/user/account_components/phone/{user}', function () {
//     return view('user.account.components.phone');
// })->name('update_contacts');

// Hello route
Route::get('/hello', function (Request $request) {

    return Attendance::active_sessions();

    $user =  User::where('email','like','agyareernest%')->first();
    $user->notify(new \App\Notifications\ActivateAccountNotification($user));

    return "user"; 
    return Attendance::find(1)->unmarked()->count();
    return Announcement::withoutGlobalScopes()->find(1)->users_seen()->get();
    // return AttendanceUser::where('user_id',null)->get()->each->delete();
    return Meeting::all();
    return User::find(1)->profile();
    return Role::where('slug','welfare')->first();
    return User::members()->where('is_student','!=',1)->get()->count();
    return User::students()->get();
    return Program::find(10)->users();
    return User::find(1012)->user_residences;
});

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/home', function () {
    return view('home');
})->name('home');

