<?php

namespace Database\Seeders;

use App\Models\Meeting;
use App\Models\Semester;
use App\Models\Announcement;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        $start_date = Semester::active_semester()->start_date; 
        $end_date = Semester::active_semester()->end_date; 
        // Create meeting annoucements
        Foreach(Meeting::all() as $meeting){
            for($i=1; $i<=rand(3,8); $i++){
                $instance =  new Announcement();
                $instance->meeting_id = $meeting->id;
                $instance->body = fake()->text();
                $instance->createable_type = "App\\Models\\Role";
                $instance->createable_id = rand(2,6);
                $instance->user_id = 1;
                $instance->is_public = true;
                $instance->is_request = false;

                $random_date = fake()->dateTimeBetween($start_date, $end_date)->format('Y-m-d');
                $instance->created_at = $random_date;
                $instance->updated_at = $random_date;

                $instance->save();
            }

        }

        // Create General Announcements
        for($i=1; $i<=70; $i++){
            $instance =  new Announcement();
            $instance->null;
            $instance->body = fake()->text();
            $instance->createable_type = "App\\Models\\Role";
            $instance->createable_id = rand(2,6);
            $instance->user_id = 1;
            $instance->is_public = true;
            $instance->is_request = false;
            
            $random_date = fake()->dateTimeBetween($start_date, $end_date)->format('Y-m-d');
            $instance->created_at = $random_date;
            $instance->updated_at = $random_date;

            $instance->save();  
        }


    }
}
