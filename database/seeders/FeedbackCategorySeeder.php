<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FeedbackCategory;
use Illuminate\Support\Str;

class FeedbackCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Website Experience',
            'Booking Process',
            'Customer Service',
            'Travel Experience',
            'Other'
        ];

        foreach ($categories as $category) {
            FeedbackCategory::firstOrCreate([
                'key' => Str::slug($category)
            ], [
                'label' => $category
            ]);
        }
    }
}
