<?php

namespace Database\Seeders;

use App\Models\Meeting;
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
            $instance->save();  
        }


    }
}
