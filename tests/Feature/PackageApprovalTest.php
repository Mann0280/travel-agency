<?php

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\Admin;
use App\Models\Destination;
use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PackageApprovalTest extends TestCase
{
    use RefreshDatabase;

    protected $agency;
    protected $admin;
    protected $destination;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->agency = Agency::create([
            'name' => 'Agency 1',
            'email' => 'agency1@test.com',
            'password' => bcrypt('password'),
            'is_verified' => true,
        ]);

        $this->admin = Admin::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);

        $this->destination = Destination::create([
            'name' => 'Test Destination',
            'location' => 'Test Location',
            'description' => 'Test Description',
        ]);
    }

    public function test_agency_package_requires_approval_to_be_visible()
    {
        // 1. Agency creates a package
        $packageData = [
            'name' => 'Hidden Package',
            'description' => 'Unapproved package',
            'price' => 1000,
            'duration' => '3 Days',
            'departure_cities' => ['Ahmedabad'],
            'travel_date' => now()->addDays(10)->format('Y-m-d'),
            'destination_id' => $this->destination->id,
        ];

        $this->actingAs($this->agency, 'agency')
            ->post(route('agency.packages.store'), $packageData);

        $package = Package::where('name', 'Hidden Package')->first();
        $this->assertFalse($package->is_approved);

        // 2. Verify it's not on the homepage
        $response = $this->get(route('home'));
        $response->assertDontSee('Hidden Package');

        // 3. Admin approves the package
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.packages.toggle-approval', $package));

        $this->assertTrue($package->fresh()->is_approved);

        // 4. Verify it's now on the homepage (if featured, or in search)
        // Let's make it featured to be sure it's on homepage
        $package->update(['is_featured' => true]);
        
        $response = $this->get(route('home'));
        $response->assertSee('Hidden Package');
    }

    public function test_admin_created_package_is_approved_by_default()
    {
        $packageData = [
            'name' => 'Admin Package',
            'description' => 'Created by admin',
            'price' => 2000,
            'duration' => '5 Days',
            'departure_cities' => ['Mumbai'],
            'travel_date' => now()->addDays(5)->format('Y-m-d'),
            'destination_id' => $this->destination->id,
            'agency_id' => $this->agency->id,
            'is_featured' => true,
        ];

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.packages.store'), $packageData);

        $package = Package::where('name', 'Admin Package')->first();
        $this->assertTrue($package->is_approved);

        $response = $this->get(route('home'));
        $response->assertSee('Admin Package');
    }
}
