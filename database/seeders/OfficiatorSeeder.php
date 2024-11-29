<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Meeting;
use App\Models\Officiator;
use Illuminate\Support\Arr;
use App\Models\OfficiatingRole;
use Illuminate\Database\Seeder;
use App\Models\Scopes\SemesterScope;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OfficiatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        foreach(Meeting::withoutGlobalScopes([SemesterScope::class])->get() as $meeting){

            $no = $meeting->meeting_type_id == 10 ? rand(10,14) : rand(1,4);

            for($i=1; $i<=$no; $i++){
                $officiator = new Officiator;
                $officiator->meeting_id = $meeting->id;
                $officiator->officiating_role_id = OfficiatingRole::all()->random()->id;
                $officiator->gender = Arr::random(['m', 'F']);

                    $is_user = Arr::random([true, false]);
                    if($is_user){
                        $user = User::all()->random();
                    }

                $officiator->fullname = $is_user ? $user->fullname : fake()->name();
                $officiator->email = $is_user ? $user->email : Arr::random([null, fake()->email()]);
                $officiator->phone = $is_user ? $user->phone : Arr::random([null, fake()->phoneNumber()]);
                $officiator->residence = $is_user ? $user->residence() ? $user->residence()->name : null : Arr::random([null, fake()->address()]);

                $officiator->save();
            }
        }

    }
}
