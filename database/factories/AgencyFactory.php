<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class AgencyFactory extends Factory
{
    protected $model = \App\Models\Agency::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'description' => fake()->paragraph(),
            'email' => fake()->unique()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'logo' => 'default-agency-logo.jpg',
            'is_verified' => false,
            'user_id' => User::factory(),
        ];
    }
}
