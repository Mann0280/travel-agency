<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
        ]);

        Review::create([
            'comment' => 'Amazing experience with ZUBEEE!',
            'rating' => 5,
            'is_approved' => true,
            'user_id' => $user->id,
            'package_id' => 1,
        ]);
    }
}
