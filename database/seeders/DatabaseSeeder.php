<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Agency;
use App\Models\Destination;
use App\Models\Package;
use App\Models\Review;
use App\Models\Banner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@travelagency.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create agency user
        $agencyUser = User::create([
            'name' => 'ZUBEEE Tours',
            'email' => 'agency@zubee.com',
            'password' => Hash::make('password'),
            'role' => 'agency',
        ]);

        // Create agency
        $agency = Agency::create([
            'name' => 'ZUBEEE Tours',
            'description' => 'Your trusted travel partner',
            'email' => 'agency@zubee.com',
            'phone' => '+91-9876543210',
            'address' => 'Ahmedabad, Gujarat',
            'is_verified' => true,
            'user_id' => $agencyUser->id,
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
                'destination_id' => 1,
                'agency_id' => $agency->id,
            ],
            [
                'name' => 'Dalhousie Getaway',
                'description' => 'Peaceful hill station with colonial charm',
                'price' => 18000,
                'duration' => '5 days',
                'departure_cities' => ['Ahmedabad'],
                'is_featured' => true,
                'destination_id' => 2,
                'agency_id' => $agency->id,
            ],
            [
                'name' => 'Kasol Backpacking',
                'description' => 'Backpacking paradise in the Himalayas',
                'price' => 22000,
                'duration' => '6 days',
                'departure_cities' => ['Ahmedabad'],
                'is_featured' => true,
                'destination_id' => 3,
                'agency_id' => $agency->id,
            ],
        ];

        foreach ($packages as $packageData) {
            Package::create($packageData);
        }

        // Create sample reviews
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
        ]);

        Review::create([
            'comment' => 'Amazing experience with ZUBEEE!',
            'rating' => 5,
            'user_id' => $user->id,
            'package_id' => 1,
        ]);

        // Create banner
        Banner::create([
            'title' => 'Explore Himachal Pradesh',
            'description' => 'Discover the beauty of Himalayan destinations',
            'image' => '/images/banner-himachal.jpg',
            'link' => '/search?to=himachal',
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }
}
