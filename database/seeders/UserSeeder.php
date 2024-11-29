<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        // User::factory()->count(500)->knust_student_member()->create();
        // User::factory()->count(50)->non_knust_student_member()->create();
        // User::factory()->count(35)->alumni_non_student_member()->create();
        // User::factory()->count(400)->alumni()->create();
        // User::factory()->count(25)->other_member()->create();

        foreach(User::members()->where('is_student','!=',1)->get() as $user){
            $user->is_worker =1;
            $user->save();
        }

    }
}
