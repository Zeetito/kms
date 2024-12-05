<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Attendance;
use App\Models\AttendanceUser;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AttendanceUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $members_count = User::members()->count();
        Foreach(Attendance::all() as $attendance){
            $count = rand(200,$members_count);
            foreach(User::members()->inRandomOrder()->take($count)->get() as $user){
                $au = new AttendanceUser;
                $au->user_id = $user->id;
                $au->attendance_id = $attendance->id;
                $au->save();
            }

        }
    }
}
