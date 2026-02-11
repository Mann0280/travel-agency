<?php

// Test script to create a partner application
// Run with: php test_partner_application.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\PartnerApplication;

// Get first user
$user = User::first();

if (!$user) {
    echo "No users found in database!\n";
    exit(1);
}

echo "Creating test application for user: {$user->name} ({$user->email})\n";

// Create test application
$application = PartnerApplication::create([
    'user_id' => $user->id,
    'agency_name' => 'Test Travel Agency',
    'business_email' => 'test@travelagency.com',
    'phone' => '+1234567890',
    'address' => '123 Main Street, Test City, Test Country',
    'description' => 'We are a professional travel agency with over 10 years of experience in organizing tours and travel packages for customers worldwide.',
    'status' => 'pending'
]);

echo "✅ Application created successfully!\n";
echo "Application ID: {$application->id}\n";
echo "Status: {$application->status}\n";
echo "\nNow check admin panel at: http://127.0.0.1:8000/admin/partner-applications\n";
