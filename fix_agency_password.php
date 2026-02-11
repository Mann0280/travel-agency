<?php

// Fix agency password script
// Run with: php fix_agency_password.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Agency;
use Illuminate\Support\Facades\Hash;

$email = 'mann281207@gmail.com';
$password = 'password123';

// Get latest agency
$agency = Agency::where('email', $email)->first();

if (!$agency) {
    echo "No agency found for $email!\n";
    exit(1);
}

echo "Updating password for agency: {$agency->name} ({$agency->email})\n";

// Set new password manually
// Since we removed 'hashed' cast, we must hash it manually.
$agency->password = Hash::make($password);
$agency->save();

echo "✅ Password updated to: $password\n";
echo "Hash: " . $agency->password . "\n";
echo "You can now login to Agency Panel with: $password\n";
