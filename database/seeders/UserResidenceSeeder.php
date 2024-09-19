<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Residence;
use App\Models\AcademicYear;
use App\Models\UserResidence;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserResidenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(AcademicYear::all() as $academic_year){
           
            foreach(User::students()->get() as $user){

                $instance = new UserResidence();
                $instance->user_id = $user->id;
                $instance->residence_id = Residence::all()->random()->id;
                $instance->academic_year_id = $academic_year->id;
                $instance->save();
            }
        }
    }
}   
