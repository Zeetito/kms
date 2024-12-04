<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Program;
use App\Models\Semester;
use App\Models\UserProgram;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // foreach(AcademicYear::all() as $academic_year){
            foreach(User::students()->get() as $user){

                $instance = new UserProgram();
                $instance->user_id = $user->id;
                $instance->year = rand(1,4);
                $instance->program_id = Program::all()->random()->id;
                $instance->academic_year_id =  Semester::active_semester()->academic_year_id;
                $instance->save();
            }
        // }
    }
}
