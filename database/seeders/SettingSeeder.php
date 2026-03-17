<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'site_name' => 'ZUBEEE',

            'site_url' => 'http://localhost:8000',
            'contact_email' => 'info@zubeee.com',
            'phone' => '+91 123 456 7890',
            'address' => '123 Travel Street, City, Country',
            'currency_code' => 'INR',
            'currency_position' => 'left',
            'primary_color' => '#17320b',
            'secondary_color' => '#a8894d',
            'package_categories' => 'adventure, hill-station, cultural, beach, desert, trekking, nature, heritage, religious',
            'meta_title' => 'ZUBEEE',
            'meta_description' => 'ZUBEEE - Your trusted partner for unforgettable travel experiences.',

            'meta_keywords' => 'travel, tours, vacation, packages, adventure, holiday',
            'site_favicon' => 'logo.PNG',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }
    }
}
