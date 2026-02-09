<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Agency;
use Illuminate\Support\Facades\Hash;

class QuickAgencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Agency::updateOrCreate(
            ['email' => 'global@travels.com'],
            [
                'name' => 'Global Travels',
                'description' => 'Your global travel partner for premium experiences.',
                'password' => Hash::make('password123'),
                'phone' => '+91-9876543211',
                'address' => 'Mumbai, Maharashtra',
                'is_verified' => true,
            ]
        );
    }
}
