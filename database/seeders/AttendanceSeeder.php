<?php

namespace Database\Seeders;

use App\Models\Meeting;
use App\Models\Attendance;
use Illuminate\Database\Seeder;
use App\Models\Scopes\SemesterScope;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(Meeting::withoutGlobalScopes([SemesterScope::class])->get() as $meeting){
            $attendance = new Attendance;
            $attendance->meeting_id = $meeting->id;
            $user_id = auth()->id();
            $attendance->save();
        }
    }
}
