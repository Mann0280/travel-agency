<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Agency;
use App\Models\Destination;
use App\Models\Package;
use App\Models\PackageAgency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PackageManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $agency;
    protected $destination;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->agency = Agency::create([
            'name' => 'Test Agency',
            'email' => 'agency@test.com',
            'password' => bcrypt('password'),
            'phone' => '1234567890',
        ]);

        $this->destination = Destination::create([
            'name' => 'Test Destination',
            'slug' => 'test-destination',
            'image' => 'dest.jpg',
            'location' => 'Test Country',
            'highlights' => ['Highlight 1'],
        ]);
        
        Storage::fake('public');
    }

    public function test_admin_can_view_packages_list()
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.packages.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.packages.index');
    }

    public function test_admin_can_create_package_with_json_fields()
    {
        $packageData = [
            'name' => 'New Adventure Package',
            'location' => 'Himalayas',
            'departure_cities' => ['Test City'],
            'description' => 'An amazing adventure.',
            'price' => 25000,
            'duration' => '5 Days/4 Nights',
            'duration_days' => 5,
            'agency_id' => $this->agency->id,
            'destination_id' => $this->destination->id,
            'start_date' => now()->addDays(10)->format('Y-m-d'),
            'end_date' => now()->addDays(15)->format('Y-m-d'),
            'category' => 'adventure',
            'status' => 'active',
            // 'image' => UploadedFile::fake()->create('package.jpg', 100),
            'itinerary' => [
                ['day' => 'Day 1: Arrival', 'activities' => ['Arrive at base camp', 'Dinner']],
                ['day' => 'Day 2: Trekking', 'activities' => ['Start trek']]
            ],
            'inclusions' => ['Meals', 'Stay'],
            'exclusions' => ['Flights'],
            'contact_info' => ['email' => 'contact@agency.com']
        ];

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.packages.store'), $packageData);

        $response->assertRedirect(route('admin.packages.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('packages', [
            'name' => 'New Adventure Package',
            'location' => 'Himalayas',
            'category' => 'adventure',
        ]);

        $package = Package::where('name', 'New Adventure Package')->first();
        $this->assertIsArray($package->itinerary);
        $this->assertEquals('Day 1: Arrival', $package->itinerary[0]['day']);
        $this->assertEquals('Meals', $package->inclusions[0]);
    }

    public function test_admin_can_update_package()
    {
        // Manual creation to match schema
        $package = Package::create([
            'name' => 'Old Package',
            'description' => 'Desc',
            'location' => 'Old Loc',
            'price' => 1000,
            'duration' => '2 days',
            'duration_days' => 2,
            'agency_id' => $this->agency->id,
            'destination_id' => $this->destination->id,
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addDays(2)->format('Y-m-d'),
            'category' => 'nature',
            'status' => 'active',
            'is_approved' => true,
            'departure_cities' => ['Test City']
        ]);

        $updateData = [
            'name' => 'Updated Package',
            'location' => 'New Loc',
            'description' => 'Updated Desc',
            'price' => 2000,
            'duration' => '3 days',
            'duration_days' => 3,
            'agency_id' => $this->agency->id,
            'destination_id' => $this->destination->id,
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addDays(3)->format('Y-m-d'),
            'category' => 'nature',
            'status' => 'inactive',
            'inclusions' => ['New Item']
        ];

        $response = $this->actingAs($this->admin, 'admin')->put(route('admin.packages.update', $package), $updateData);

        $response->assertRedirect(route('admin.packages.index'));
        $this->assertDatabaseHas('packages', ['name' => 'Updated Package', 'price' => 2000, 'status' => 'inactive']);
    }

    public function test_admin_can_add_agency_to_package()
    {
         $package = Package::create([
            'name' => 'Package with Agency',
            'description' => 'Desc',
             'location' => 'Loc',
            'price' => 1000,
            'duration' => '2 days',
             'duration_days' => 2,
            'agency_id' => $this->agency->id,
            'destination_id' => $this->destination->id,
             'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addDays(2)->format('Y-m-d'),
             'category' => 'nature',
             'departure_cities' => ['Test City']
        ]);

        $anotherAgency = Agency::create([
            'name' => 'Another Agency',
            'email' => 'another@test.com',
            'password' => bcrypt('password'),
            'phone' => '0987654321',
        ]);

        $data = [
            'agency_id' => $anotherAgency->id,
            'price' => 15000,
            'commission' => 10,
            'duration' => '5 Days',
            'date' => '10 Dec',
            'status' => 'active',
        ];

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.packages.agencies.store', $package), $data);

        $response->assertRedirect(); // Back
        $this->assertDatabaseHas('package_agencies', [
            'package_id' => $package->id,
            'agency_id' => $anotherAgency->id,
            'price' => 15000
        ]);
    }

    public function test_admin_can_remove_agency_from_package()
    {
        $package = Package::create([
            'name' => 'Package with Agency',
            'description' => 'Desc',
             'location' => 'Loc',
            'price' => 1000,
            'duration' => '2 days',
             'duration_days' => 2,
            'agency_id' => $this->agency->id,
            'destination_id' => $this->destination->id,
             'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addDays(2)->format('Y-m-d'),
             'category' => 'nature',
             'departure_cities' => ['Test City']
        ]);

        $packageAgency = PackageAgency::create([
            'package_id' => $package->id,
            'agency_id' => $this->agency->id,
            'price' => 10000,
            'commission' => 5,
            'status' => 'active'
        ]);

        $response = $this->actingAs($this->admin, 'admin')->delete(route('admin.package-agencies.destroy', $packageAgency));

        $response->assertRedirect();
        $this->assertDatabaseMissing('package_agencies', ['id' => $packageAgency->id]);
    }

    public function test_admin_can_create_package_with_branches()
    {
        $packageData = [
            'name' => 'Package with Branches',
            'location' => 'City Center',
            'description' => 'A package with branches.',
            'price' => 5000,
            'duration' => '2 Days',
            'duration_days' => 2,
            'agency_id' => $this->agency->id,
            'destination_id' => $this->destination->id,
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addDays(2)->format('Y-m-d'),
            'category' => 'business',
            'status' => 'active',
            'departure_cities' => ['Test City'],
            'branches' => [
                ['city' => 'Mumbai', 'address' => '123 Street', 'phone' => '1234567890'],
                ['city' => 'Delhi', 'address' => '456 Avenue', 'phone' => '0987654321']
            ]
        ];

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.packages.store'), $packageData);

        $response->assertRedirect(route('admin.packages.index'));
        $this->assertDatabaseHas('packages', ['name' => 'Package with Branches']);

        $package = Package::where('name', 'Package with Branches')->first();
        $this->assertIsArray($package->branches);
        $this->assertCount(2, $package->branches);
        $this->assertEquals('Mumbai', $package->branches[0]['city']);
    }
}
