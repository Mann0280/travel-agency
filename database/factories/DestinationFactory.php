<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DestinationFactory extends Factory
{
    protected $model = \App\Models\Destination::class;

    public function definition(): array
    {
        return [
            'name' => fake()->city(),
            'description' => fake()->paragraph(),
            'location' => fake()->city() . ', ' . fake()->state(),
            'highlights' => [
                fake()->word(),
                fake()->word(),
                fake()->word(),
            ],
            'is_active' => true,
        ];
    }
}
