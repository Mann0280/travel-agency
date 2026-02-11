<?php

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\Booking;
use App\Models\Package;
use App\Models\User;
use App\Models\Admin;
use App\Models\Destination;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemAuditFixTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_user_stats_are_calculated_correctly()
    {
        // 1. Setup Admin
        $admin = Admin::create([
            'name' => 'Admin Test',
            'email' => 'admin.test@example.com',
            'password' => bcrypt('password')
        ]);
        
        // Setup Dependencies
        $agency = Agency::create(['name' => 'Test Agency', 'email' => 'test@test.com', 'password' => 'password']);
        $destination = Destination::create(['name' => 'Goa', 'location' => 'India', 'slug' => 'goa']);
        $package = Package::create([
            'name' => 'Test Package',
            'description' => 'Test Description',
            'duration' => '3 Days',
            'departure_cities' => ['Delhi'],
            'price' => 1000,
            'agency_id' => $agency->id,
            'destination_id' => $destination->id,
            'is_approved' => true,
            'travel_date' => now()->addDays(10)
        ]);
        
        // 2. Setup User and Bookings
        $user = User::factory()->create();
        
        // Confirmed Booking 1 (Amount: 1000)
        Booking::create([
            'user_id' => $user->id,
            'package_id' => $package->id,
            'total_amount' => 1000,
            'status' => 'confirmed',
            'travel_date' => now(),
            'booking_source' => 'web',
            'number_of_travelers' => 1
        ]);

        // Confirmed Booking 2 (Amount: 2000)
        Booking::create([
            'user_id' => $user->id,
            'package_id' => $package->id,
            'total_amount' => 2000,
            'status' => 'confirmed',
            'travel_date' => now(),
            'booking_source' => 'web',
            'number_of_travelers' => 1
        ]);

        // Pending Booking (Amount: 500) - Should count in bookingsCount but NOT in Revenue
        Booking::create([
            'user_id' => $user->id,
            'package_id' => $package->id,
            'total_amount' => 500,
            'status' => 'pending',
            'travel_date' => now(),
            'booking_source' => 'web',
            'number_of_travelers' => 1
        ]);

        // 3. Test Index Page (Global Stats)
        $response = $this->actingAs($admin, 'admin')->get(route('admin.users.index'));
        $response->assertStatus(200);
        
        // Assert View Data
        $response->assertViewHas('totalBookings', 3);
        $response->assertViewHas('totalRevenue', 3000.00); // 1000 + 2000

        // 4. Test Edit Page (Individual User Stats)
        $response = $this->actingAs($admin)->get(route('admin.users.edit', $user));
        $response->assertStatus(200);

        $response->assertViewHas('bookingsCount', 3);
        $response->assertViewHas('totalSpent', 3000.00);
    }

    /** @test */
    public function search_duration_filter_parses_complex_strings()
    {
        // Setup Destination
        $destination = Destination::create(['name' => 'Goa', 'location' => 'India', 'slug' => 'goa']);
        $agency = Agency::create(['name' => 'Test Agency', 'email' => 'test@test.com', 'password' => 'password']);

        // 1. Create Packages with different duration strings
        $p1 = Package::create([
            'name' => 'Short Trip',
            'description' => 'A short trip description',
            'departure_cities' => ['Mumbai'],
            'from_city' => 'Mumbai',
            'duration' => '3 Days',
            'is_approved' => true,
            'destination_id' => $destination->id,
            'agency_id' => $agency->id,
            'price' => 5000,
            'travel_date' => now()->addDays(10)
        ]);

        $p2 = Package::create([
            'name' => 'Medium Trip',
            'description' => 'A medium trip description',
            'departure_cities' => ['Mumbai'],
            'from_city' => 'Mumbai',
            'duration' => '5N/6D', // Should be parsed as 6 days
            'is_approved' => true,
            'destination_id' => $destination->id,
            'agency_id' => $agency->id,
            'price' => 10000,
            'travel_date' => now()->addDays(10)
        ]);

        $p3 = Package::create([
            'name' => 'Long Trip',
            'description' => 'A long trip description',
            'departure_cities' => ['Mumbai'],
            'from_city' => 'Mumbai',
            'duration' => '10 Days',
            'is_approved' => true,
            'destination_id' => $destination->id,
            'agency_id' => $agency->id,
            'price' => 20000,
            'travel_date' => now()->addDays(10)
        ]);

        // 2. Search for duration "4-7" days
        // Should include P2 (6 days), exclude P1 (3 days) and P3 (10 days)
        $response = $this->get(route('search', ['duration' => '4-7']));
        
        $response->assertStatus(200);
        $packages = $response->viewData('filteredPackages');

        $this->assertFalse($packages->contains($p1), 'Short Trip should be excluded');
        $this->assertTrue($packages->contains($p2), 'Medium Trip (5N/6D) should be included');
        $this->assertFalse($packages->contains($p3), 'Long Trip should be excluded');
    }

    /** @test */
    public function agency_dashboard_stats_are_correct()
    {
        // 1. Setup Agency
        $agency = Agency::create(['name' => 'Stats Agency', 'email' => 'stats@agency.com', 'password' => bcrypt('password')]);
        
        // Create Destination first
        $destination = Destination::create(['name' => 'Goa', 'location' => 'India', 'slug' => 'goa']);

        // 2. Setup Package owned by Agency
        $package = Package::create([
            'name' => 'My Package',
            'description' => 'Description',
            'departure_cities' => ['Delhi'],
            'duration' => '5 Days',
            'agency_id' => $agency->id,
            'price' => 5000,
            'is_approved' => true,
            'destination_id' => $destination->id,
            'travel_date' => now()->addDays(10)
        ]);

        // 3. Setup Booking with clicks
        Booking::create([
            'package_id' => $package->id,
            'whatsapp_clicks' => 10,
            'call_clicks' => 5,
            'button_clicks' => 18, // 10+5+3(others)
            'booking_source' => 'button_click',
            'travel_date' => now(),
            'number_of_travelers' => 1,
            'total_amount' => 0 // Optional but good for consistency
        ]);

        // 4. Login as Agency
        $response = $this->actingAs($agency, 'agency')
                         ->get(route('agency.dashboard'));

        $response->assertStatus(200);
        $stats = $response->viewData('stats');

        $this->assertEquals(10, $stats['whatsapp_clicks']);
        $this->assertEquals(5, $stats['call_clicks']);
        $this->assertEquals(18, $stats['total_clicks']);
    }

    /** @test */
    public function agency_can_create_and_update_package_with_all_fields()
    {
        $agency = Agency::create(['name' => 'Creator Agency', 'email' => 'creator@agency.com', 'password' => bcrypt('password')]);
        $destination = Destination::create(['name' => 'Manali', 'location' => 'Himachal', 'slug' => 'manali']);

        // 1. Test Create
        $response = $this->actingAs($agency, 'agency')->post(route('agency.packages.store'), [
            'name' => 'Full Package',
            'description' => 'Detailed description',
            'price' => 15000,
            'duration' => '5N/6D',
            'from_city' => 'Delhi',
            'departure_cities' => ['Delhi', 'Chandigarh'],
            'location' => 'Manali, Himachal',
            'destination_id' => $destination->id,
            'image' => UploadedFile::fake()->create('cover.jpg', 100),
            
            // Fields present in form but missing/mismatched in controller
            'start_date' => now()->addDays(5)->format('Y-m-d'),
            'end_date' => now()->addDays(10)->format('Y-m-d'),
            'duration_days' => 6,
            'category' => 'hill-station',
            'status' => 'active',
            'available_months' => ['May', 'June'],
            'contact_info' => ['email' => 'contact@agency.com'],
            'branches' => [
                ['city' => 'Manali', 'phone' => '1234567890', 'address' => 'Mall Road']
            ],
        ]);

        if ($response->status() !== 302) {
             $response->dump();
        }
        
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('packages', [
            'name' => 'Full Package',
            'category' => 'hill-station',
            'location' => 'Manali, Himachal'
        ]);
        
        $package = Package::where('name', 'Full Package')->first();
        
        // 2. Test Update
        $updateResponse = $this->actingAs($agency, 'agency')->put(route('agency.packages.update', $package->id), [
            'name' => 'Updated Package',
            'description' => 'Updated description',
            'price' => 16000,
            'duration' => '6N/7D',
            'from_city' => 'Delhi',
            'departure_cities' => ['Delhi'],
            'location' => 'Manali Updated',
            'destination_id' => $destination->id,
            'start_date' => now()->addDays(10)->format('Y-m-d'),
            'end_date' => now()->addDays(17)->format('Y-m-d'),
            'duration_days' => 7,
            'category' => 'adventure',
            'status' => 'active',
        ]);
        
        $updateResponse->assertSessionHasNoErrors();
        $this->assertDatabaseHas('packages', [
            'id' => $package->id,
            'name' => 'Updated Package',
            'category' => 'adventure'
        ]);
    }
}
