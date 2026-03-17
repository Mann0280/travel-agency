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
        $this->call([
            SettingSeeder::class,
            AdminSeeder::class,
            AgencySeeder::class,
            UserSeeder::class,
            FeedbackCategorySeeder::class,
            AgencyContactSeeder::class,
        ]);
    }
}
