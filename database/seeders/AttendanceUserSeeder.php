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

            // Seed Some Guests too
            for($i = 0; $i < rand(1,10); $i++){
                $au = new AttendanceUser;
                $au->user_id = null;
                $au->attendance_id = $attendance->id;

                $details = [
                    'name' => fake()->name(),
                    'email' => fake()->email(),
                    'is_member' => rand(0,1) == 1 ? true : false,
                    'gender' => rand(0,1) == 1 ? "m" : "f",
                    'phone' => fake()->phoneNumber(),
                ];

                $au->guest_details = json_decode(json_encode($details), true);

                $au->save();
            }

        }
    }
}
