<?php

use App\Models\Role;
use App\Models\User;
use App\Models\Zone;
use App\Models\Record;
use App\Models\Report;
use App\Models\Meeting;
use App\Models\Program;
use App\Models\Residence;
use App\Models\MeetingType;
use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Models\UserResidence;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\RoleController;
use App\Http\Controllers\Api\V1\SeenController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\ZoneController;
use App\Http\Controllers\Api\V1\LoginController;
use App\Http\Controllers\Api\V1\RecordController;
use App\Http\Controllers\Api\V1\ReportController;
use App\Http\Controllers\Api\V1\MeetingController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\ProgramController;
use App\Http\Controllers\Api\V1\ResidenceController;
use App\Http\Controllers\Api\V1\AttendanceController;
use App\Http\Controllers\Api\V1\OfficiatorController;
use App\Http\Controllers\Api\V1\RecordItemController;
use App\Http\Controllers\Api\V1\MeetingTypeController;
use App\Http\Controllers\Api\V1\UserProgramController;
use App\Http\Controllers\Api\V1\AnnouncementController;
use App\Http\Controllers\Api\V1\ReportRecordController;
use App\Http\Controllers\Api\V1\SemesterUserController;
use App\Http\Controllers\Api\V1\PasswordResetController;
use App\Http\Controllers\Api\V1\UserResidenceController;
use App\Http\Controllers\Api\V1\AttendanceUserController;
use App\Http\Controllers\Api\V1\OfficiatingRoleController;

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

// SEEN
    // Store
    Route::post('/seen/{type}/{id}', [SeenController::class, 'Store'])
    ->middleware('auth:sanctum')
    ;

    // Destroy
    Route::delete('/seen/{type}/{id}', [SeenController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ;


// ATTENDANCE
    // Index
    Route::get('/attendances', [AttendanceController::class, 'index']);

    // Attendance User
    // Mark user for a particular attendance session
    Route::post('/attendances/{meeting}/user', [AttendanceUserController::class, 'store'])
    ->middleware('auth:sanctum');

    // Update Attendance user
    Route::put('/attendance_users/{attendance_user}', [AttendanceUserController::class, 'update'])
    ->middleware('auth:sanctum');

    // Methods
    // Get Attendees
    Route::get('/attendances/{attendance}/attendees', [AttendanceUserController::class, 'attendees'])
    ->middleware('auth:sanctum');
    ;

    // Get Absentees
    Route::get('/attendances/{attendance}/absentees', [AttendanceUserController::class, 'absentees'])
    ->middleware('auth:sanctum');
    ;

    // Get unmarked members 
    Route::get('/attendances/{attendance}/unmarked', [AttendanceUserController::class, 'unmarked'])
    ->middleware('auth:sanctum');
    ;

    // Get guests
    Route::get('/attendances/{attendance}/guests', [AttendanceUserController::class, 'guests'])
    ->middleware('auth:sanctum');
    ;


// SEMESTER USER
    // Index
    Route::get('/semester_users', [SemesterUserController::class, 'index'])
    ->middleware('auth:sanctum')
    ;

    // Store or Update
    Route::post('/semester_users/{user}', [SemesterUserController::class, 'storeOrUpdate'])
    ->middleware('auth:sanctum')
    ;

    // Delete
    Route::delete('/semester_users', [SemesterUserController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ;


    

// ROLES
    // Index
    Route::get('/roles', [RoleController::class, 'index'])
    ->middleware('auth:sanctum')
    ;

    // Show 
    Route::get('/roles/{role}', [RoleController::class, 'show'])
    ->middleware('auth:sanctum')
    ;

    // Store
    Route::post('/roles', [RoleController::class, 'store'])
    ->middleware('auth:sanctum')
    ;


    // USER
    // Index
    Route::get('/role/{role}/users', [RoleController::class, 'users'])
    ->middleware('auth:sanctum')
    ;

    // Get all ministry members
    Route::middleware('auth:sanctum')->get('/roles/ministry_members', function () {
        $users = User::ministry_members();
        return response()->json($users);
    });

    
// USERRESIDENCE
    // Index
    Route::get('/user_residences', [UserResidenceController::class, 'index'])
    ->middleware('auth:sanctum')
    ;

    // Store UserResidence
    Route::post('/user_residences/{user}', [UserResidenceController::class, 'store'])
    ->middleware('auth:sanctum')
    ;

    // Update UserResidence
    Route::put('/user_residences/{user}', [UserResidenceController::class, 'update'])
    ->middleware('auth:sanctum')
    ;

    // Delete UserResidence
    Route::delete('/user_residences/{user}', [UserResidenceController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ;

// PROGRAM
    // Index
    Route::get('/programs', [ProgramController::class, 'index'])
    // ->middleware('auth:sanctum')
    ;

    // Show
    Route::get('/programs/{program}', [ProgramController::class, 'show'])
    // ->middleware('auth:sanctum')
    ;

    // Store Program
    Route::post('/programs', [ProgramController::class, 'store'])
    ->middleware('auth:sanctum')
    ;

    // Update Program
    Route::put('/programs/{program}', [ProgramController::class, 'update'])
    ->middleware('auth:sanctum')
    ;

    // Delete Program
    Route::delete('/programs/{program}', [ProgramController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ;

    // Methods
    // Users
    Route::middleware('auth:sanctum')->get('/programs/{program}/users', function (Request $request, Program $program) {
        return response()->json($program->users());
    });


// USER PROGRAM
    // Index
    Route::get('/user_programs', [UserProgramController::class, 'index'])
    ->middleware('auth:sanctum')
    ;

    // Show
    // Route::get('/user_programs/{user}', [UserProgramController::class, 'show'])
    // ->middleware('auth:sanctum')
    // ;

    // Store
    Route::post('/user_programs/{user}', [UserProgramController::class, 'store'])
    ->middleware('auth:sanctum')
    ;

    // Update
    Route::put('/user_programs/{user}', [UserProgramController::class, 'update'])
    ->middleware('auth:sanctum')
    ;

    // Delete
    Route::delete('/user_programs/{user}', [UserProgramController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ;


// ZONE
    // Index
    Route::get('/zones', [ZoneController::class, 'index'])
    // ->middleware('auth:sanctum')
    ;

    // Show
    Route::get('/zones/{zone}', [ZoneController::class, 'show'])
    ->middleware('auth:sanctum')
    ;

    // ---Store
    Route::post('/zones', [ZoneController::class, 'store'])
    ->middleware('auth:sanctum')
    ;


    // Update
    Route::put('/zones/{zone}', [ZoneController::class, 'update'])
    ->middleware('auth:sanctum')
    ;

    // Delete
    Route::delete('/zones/{zone}', [ZoneController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ;

    // USER
    // Index
    Route::middleware('auth:sanctum')->get('/zones/{zone}/users', function (Zone $zone) {
        return response()->json($zone->users());
    });
    
    // RESIDENCE
    // Index
    Route::get('/zones/{zone}/residences', function (Zone $zone) {
        return response()->json($zone->residences);
    });
    
// RESIDENCE
    // Get residences
    Route::middleware('auth:sanctum')->get('/residences', function () {
        $residences = Residence::with('zone')->orderByDesc('name')->get();
        return response()->json($residences);
    });

    // Store Residence
    Route::post('/residences', [ResidenceController::class, 'store'])
    ->middleware('auth:sanctum')
    ;

    // Update Residence
    Route::put('/residences/{residence}', [ResidenceController::class, 'update'])
    ->middleware('auth:sanctum')
    ;

    // Delete Residence
    Route::delete('/residences/{residence}', [ResidenceController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ;

    // Methods
    //Get zone
    Route::middleware('auth:sanctum')->get('/residences/{residence}/zone', function (Request $request, Residence $residence) {
        return response()->json($residence->zone);
    });

    //Get users
    Route::middleware('auth:sanctum')->get('/residences/{residence}/users', function (Request $request, Residence $residence) {
        return response()->json($residence->users());
    });

// MEETING
    // Index
    Route::get('/meetings', [MeetingController::class, 'index'])
    ->middleware('auth:sanctum')
    ;

    // Show
    Route::get('/meetings/{meeting}', [MeetingController::class, 'show'])
    ->middleware('auth:sanctum')
    ;

    // Store
    Route::post('/meetings', [MeetingController::class, 'store'])
    ->middleware('auth:sanctum')
    ;

    // Update
    Route::put('/meetings/{meeting}', [MeetingController::class, 'update'])
    ->middleware('auth:sanctum')
    ;

    // Delete
    Route::delete('/meetings/{meeting}', [MeetingController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ;

    // Methods
    // announcements
    Route::middleware('auth:sanctum')->get('/meetings/{meeting}/announcements', function (Request $request, Meeting $meeting) {
        return response()->json($meeting->announcements);
    });

    // return the attendance session for a particular meeting
    Route::middleware('auth:sanctum')->get('/meetings/{meeting}/attendance', function (Request $request, Meeting $meeting) {
        return response()->json($meeting->attendance);
    });

    // Start Or End Attendance for meeting
    Route::post('/attendances/{meeting}/toggle', [MeetingController::class, 'startOrEndAttendance'])
    ->middleware('auth:sanctum')
    ;
    

    

// MEETING TYPE
    // Index
    Route::get('/meeting_types', [MeetingTypeController::class, 'index'])
    ->middleware('auth:sanctum')
    ;

    // Show
    Route::get('/meeting_types/{meeting_type}', [MeetingTypeController::class, 'show'])
    ->middleware('auth:sanctum')
    ;

    // Store
    Route::post('/meeting_types', [MeetingTypeController::class, 'store'])
    ->middleware('auth:sanctum')
    ;   
    
    // Update
    Route::put('/meeting_types/{meeting_type}', [MeetingTypeController::class, 'update'])
    ->middleware('auth:sanctum')
    ;

    // Delete
    Route::delete('/meeting_types/{meeting_type}', [MeetingTypeController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ;

    // Methods
    // Meetings
    Route::middleware('auth:sanctum')->get('/meeting_types/{meeting_type}/meetings', function (Request $request, MeetingType $meeting_type) {
        return response()->json($meeting_type->meetings);
    });

// OFFICIATING ROLE
    // Index
    Route::get('/officiating_roles', [OfficiatingRoleController::class, 'index'])
    ->middleware('auth:sanctum')
    ;

    // Show
    Route::get('/officiating_roles/{officiating_role}', [OfficiatingRoleController::class, 'show'])
    ->middleware('auth:sanctum')
    ;

    // Store
    Route::post('/officiating_roles', [OfficiatingRoleController::class, 'store'])
    ->middleware('auth:sanctum')
    ;

    // Update
    Route::put('/officiating_roles/{officiating_role}', [OfficiatingRoleController::class, 'update'])
    ->middleware('auth:sanctum')
    ;

    // Delete
    Route::delete('/officiating_roles/{officiating_role}', [OfficiatingRoleController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ;

    // Methods

// OFFICIATORS
    // Index
    Route::get('/officiators', [OfficiatorController::class, 'index'])
    ->middleware('auth:sanctum')
    ;

    // Show
    Route::get('/officiators/{officiator}', [OfficiatorController::class, 'show'])
    ->middleware('auth:sanctum')
    ;

    // Store
    Route::post('/officiators', [OfficiatorController::class, 'store'])
    ->middleware('auth:sanctum')
    ;

    // Update
    Route::put('/officiators/{officiator}', [OfficiatorController::class, 'update'])
    ->middleware('auth:sanctum')
    ;

    // Delete
    Route::delete('/officiators/{officiator}', [OfficiatorController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ;

// RECORD
    // Index
    Route::get('/records', [RecordController::class, 'index'])
    ->middleware('auth:sanctum')
    ;

    // Show
    Route::get('/records/{record}', [RecordController::class, 'show'])
    ->middleware('auth:sanctum')
    ;

    // Store
    Route::post('/records', [RecordController::class, 'store'])
    ->middleware('auth:sanctum')
    ;

    // Update
    Route::put('/records/{record}', [RecordController::class, 'update'])
    ->middleware('auth:sanctum')
    ;

    // Delete
    Route::delete('/records/{record}', [RecordController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ;

    // Methods
    // RecordItems
    Route::middleware('auth:sanctum')->get('/records/{record}/record_items', function (Request $request, Record $record) {
        return response()->json($record->record_items);
    });

// RECORD ITEM
    // Index
    Route::get('/record_items', [RecordItemController::class, 'index'])
    ->middleware('auth:sanctum')
    ;

    // Show
    Route::get('/record_items/{record_item}', [RecordItemController::class, 'show'])
    ->middleware('auth:sanctum')
    ;

    // Store
    Route::post('/record_items', [RecordItemController::class, 'store'])
    ->middleware('auth:sanctum')
    ;

    // Update
    Route::put('/record_items/{record_item}', [RecordItemController::class, 'update'])
    ->middleware('auth:sanctum')
    ;

    // Delete
    Route::delete('/record_items/{record_item}', [RecordItemController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ;

// REPORT
    // Index
    Route::get('/reports', [ReportController::class, 'index'])
    ->middleware('auth:sanctum')
    ;

    // Show
    Route::get('/reports/{report}', [ReportController::class, 'show'])
    ->middleware('auth:sanctum')
    ;

    // Store
    Route::post('/reports', [ReportController::class, 'store'])
    ->middleware('auth:sanctum')
    ;

    // Update
    Route::put('/reports/{report}', [ReportController::class, 'update'])
    ->middleware('auth:sanctum')
    ;

    // Delete
    Route::delete('/reports/{report}', [ReportController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ;

    // Methods
    // ReportRecords
    Route::middleware('auth:sanctum')->get('/reports/{report}/report_records', function (Request $request, Report $report) {
        return response()->json($report->report_records);
    });


// REPORT RECORD
    // Index
    Route::get('/report_records', [ReportRecordController::class, 'index'])
    ->middleware('auth:sanctum')
    ;

    // Show
    Route::get('/report_records/{report_record}', [ReportRecordController::class, 'show'])
    ->middleware('auth:sanctum')
    ;

    // Store
    Route::post('/report_records/{report}', [ReportRecordController::class, 'store'])
    ->middleware('auth:sanctum')
    ;

    // Update
    Route::put('/report_records/{report_record}', [ReportRecordController::class, 'update'])
    ->middleware('auth:sanctum')
    ;

    // Delete
    Route::delete('/report_records/{report_record}', [ReportRecordController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ;

// ANNOUCNCEMENT
    // Index
    Route::get('/announcements', [AnnouncementController::class, 'index'])
    ->middleware('auth:sanctum')
    ;

    // Show
    Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show'])
    ->middleware('auth:sanctum')
    ;

    // Store
    Route::post('/announcements', [AnnouncementController::class, 'store'])
    ->middleware('auth:sanctum')
    ;

    // Update
    Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update'])
    ->middleware('auth:sanctum')
    ;

    // Delete
    Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])
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

        // Delete A users account
        Route::delete('/users/{user}', [UserController::class, 'destroy'])
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

            // Get male Members
            Route::middleware('auth:sanctum')->get('/male_members', function () {
                $users = User::male_members();
                return response()->json($users);
            });

            // Get female Members
            Route::middleware('auth:sanctum')->get('/female_members', function () {
                $users = User::female_members();
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
        // ->middleware('auth:sanctum')
        ;

        // Update
        Route::post('/update_user/{user}', [UserController::class, 'update'])
        ->middleware('auth:sanctum')
        ;

        // Update User Account Status
        Route::put('/user_account/{user}', [UserController::class, 'user_account'])
        ->middleware('auth:sanctum')
        ;

    // PROFILE
        // Get user profile
        Route::middleware('auth:sanctum')->get('/user/{user}/profile', function (User $user) {
            return $user->profile();
        });

        // Update User profile
        Route::put('/user/{user}/profile', [ProfileController::class, 'update'])
        ->middleware('auth:sanctum')
        ;

        // Update User profile pic
        Route::post('/user/{user}/profile_pic', [ProfileController::class, 'update_profile_pic'])
        ->middleware('auth:sanctum')
        ;

        // AUTH
    // Login
    Route::post('/login', [LoginController::class, 'login'])
    ->name('login')
    ;

    // Logout
    Route::middleware('auth:sanctum')->post('/logout', [LoginController::class, 'logout']);

    // Password Reset and Forget
    Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword'])->name('password.email');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.reset');



// hello route
Route::get('/hello', function (Request $request) {
    return auth()->user();
    return App\Models\User::find(6)->name;
    return App\Models\User::find(1)->zone_id;
    return App\Models\User::find(1)->residence();
    // return getAcademicYearId();
    return 'hello';
})->middleware('auth:sanctum');