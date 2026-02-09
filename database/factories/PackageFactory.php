<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Agency;
use App\Models\Destination;

class PackageFactory extends Factory
{
    protected $model = \App\Models\Package::class;

    public function definition(): array
    {
        return [
            'name' => fake()->catchPhrase(),
            'description' => fake()->paragraph(),
            'price' => fake()->numberBetween(10000, 100000),
            'duration' => fake()->randomElement(['3 days', '5 days', '7 days', '10 days']),
            'departure_cities' => ['Ahmedabad', 'Mumbai', 'Delhi'],
            'is_featured' => false,
            'destination_id' => Destination::factory(),
            'agency_id' => Agency::factory(),
        ];
    }
}
