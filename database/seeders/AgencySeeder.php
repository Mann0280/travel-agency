<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Destination;
use App\Models\Package;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AgencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create agency
        $agency = Agency::create([
            'name' => 'ZUBEEE Tours',
            'description' => 'Your trusted travel partner',
            'email' => 'agency@zubee.com',
            'password' => Hash::make('password'),
            'phone' => '+91-9876543210',
            'address' => 'Ahmedabad, Gujarat',
            'is_verified' => true,
        ]);

        // Create destinations
        $destinations = [
            [
                'name' => 'Spiti Valley',
                'description' => 'Experience the breathtaking beauty of Spiti Valley',
                'location' => 'Himachal Pradesh',
                'highlights' => ['Mountain views', 'Buddhist monasteries', 'High altitude lakes'],
            ],
            [
                'name' => 'Dalhousie',
                'description' => 'Peaceful hill station with colonial charm',
                'location' => 'Himachal Pradesh',
                'highlights' => ['Colonial architecture', 'Pine forests', 'Dhauladhar mountains'],
            ],
            [
                'name' => 'Kasol',
                'description' => 'Backpacking paradise in the Himalayas',
                'location' => 'Himachal Pradesh',
                'highlights' => ['River rafting', 'Trekking', 'Cafes and culture'],
            ],
        ];

        foreach ($destinations as $destData) {
            Destination::create($destData);
        }

        // Create packages
        $packages = [
            [
                'name' => 'Spiti Valley Adventure',
                'description' => 'Experience the breathtaking beauty of Spiti Valley',
                'price' => 25000,
                'duration' => '7 days',
                'departure_cities' => ['Ahmedabad'],
                'is_featured' => true,
                'is_approved' => true,
                'destination_id' => 1,
                'agency_id' => $agency->id,
                'travel_date' => now()->addDays(30),
            ],
            [
                'name' => 'Dalhousie Getaway',
                'description' => 'Peaceful hill station with colonial charm',
                'price' => 18000,
                'duration' => '5 days',
                'departure_cities' => ['Ahmedabad'],
                'is_featured' => true,
                'is_approved' => true,
                'destination_id' => 2,
                'agency_id' => $agency->id,
                'travel_date' => now()->addDays(15),
            ],
            [
                'name' => 'Kasol Backpacking',
                'description' => 'Backpacking paradise in the Himalayas',
                'price' => 22000,
                'duration' => '6 days',
                'departure_cities' => ['Ahmedabad'],
                'is_featured' => true,
                'is_approved' => true,
                'destination_id' => 3,
                'agency_id' => $agency->id,
                'travel_date' => now()->addDays(45),
            ],
        ];

        foreach ($packages as $packageData) {
            Package::create($packageData);
        }
    }
}
