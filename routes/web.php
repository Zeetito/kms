<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Jobs\UserRegisteredNotificationJob;
use App\Notifications\UserRegisteredNotification;

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

// Hello route
Route::get('/hello', function () {
    $user = User::find(1011);
    UserRegisteredNotificationJob::dispatch($user)->delay(now()->addSeconds(30));
    // $user->notify(new UserRegisteredNotification());
    return "afa";
    return User::ministry_members();
    return User::find(2)->roles()->count();
    return getAcademicYearId();
    
    // return App\Models\User::all()->update(['is_active'=>1]);
    return App\Models\UserResidence::all();
    return App\Models\User::workers_members()->get()->pluck('is_worker');
    // return App\Models\User::query()->update(['is_active' => 1]);

    return "Hello Sir";
});
