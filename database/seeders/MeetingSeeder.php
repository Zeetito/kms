<?php

namespace Database\Seeders;

use App\Models\Meeting;
use App\Models\Semester;
use App\Models\MeetingType;
use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MeetingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach([5,6] as $sem){

            for($i=1; $i<=70; $i++){
                $meeting = new Meeting;
                $meeting->meeting_type_id = MeetingType::all()->random()->id;
                $meeting->program_name = Arr::random([null, $meeting->meeting_type->name, fake()->sentence]);
                $meeting->is_special = Arr::random([true, false]);
                $meeting->allows_question = Arr::random([true, false]);
                $meeting->description = Arr::random([null, fake()->sentence]);
                $meeting->start_date = fake()->date();
                $meeting->end_date = null;
                $meeting->start_time = fake()->optional()->time();
                $meeting->end_time = null;
                $meeting->venue = Arr::random(["Unity Hall Basement (Conti Basement)","Providence Hostel", null, fake()->sentence]);
                $meeting->location = Arr::random([null, fake()->sentence]);
                $meeting->semester_id = $sem;
                $meeting->save();
            }

        }
    }
}
