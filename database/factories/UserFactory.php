<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'firstname' => fake()->firstname(),
            'othername' => rand(0,1) == 0 ? null : fake()->firstname(),
            'lastname' => fake()->lastname(),
            'gender' => rand(0,1) == 1 ? "m" : "f", 
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function knust_student_member()
    {
        return $this->state(function (array $attributes) {
            return [
                // User attributes...
                'is_member' => '1',
                'is_knust_affiliate' => '1',
                'is_alumni' => '0',
                'status' => 'student',
            ];
        });
    }

    public function non_knust_student_member()
    {
        return $this->state(function (array $attributes) {
            return [
                // User attributes...
                'is_member' => '1',
                'is_knust_affiliate' => '0',
                'is_alumni' => '0',
                'status' => 'student',  
            ];
        });
    }

    public function alumni_non_student_member()
    {
        return $this->state(function (array $attributes) {
            return [
                // User attributes...
                'is_member' => 1,
                'is_knust_affiliate' => '1',
                'is_alumni' => '1',
                'status' => 'Worker/Ns',
            ];
        });
    }

    public function alumni()
    {
        return $this->state(function (array $attributes) {
            return [
                // User attributes...
                'is_member' => 0,
                'is_knust_affiliate' => '1',
                'is_alumni' => '1',
                'status' => 'Alumni',

            ];
        });
    }

    public function other_member()
    {
        return $this->state(function (array $attributes) {
            return [
                // User attributes...
                'is_member' => '1',
                'is_knust_affiliate' => '0',
                'status' => 'Worker/Ns',
                'is_alumni' => '0',
                

            ];
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
