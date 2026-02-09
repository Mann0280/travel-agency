<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Package;
use App\Models\Agency;
use App\Models\Destination;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPackageManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Agency $agency;
    protected Destination $destination;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create(['role' => 'admin']);
        
        $agencyUser = User::factory()->create(['role' => 'agency']);
        $this->agency = Agency::factory()->create(['user_id' => $agencyUser->id]);
        $this->destination = Destination::factory()->create();
    }

    /**
     * Test admin can view package list
     */
    public function test_admin_can_view_package_list(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/packages');
        $response->assertStatus(200);
    }

    /**
     * Test non-admin cannot access package list
     */
    public function test_non_admin_cannot_access_package_list(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        
        $response = $this->actingAs($user)->get('/admin/packages');
        $response->assertStatus(403);
    }

    /**
     * Test admin can create package
     */
    public function test_admin_can_create_package(): void
    {
        $packageData = [
            'name' => 'Test Package',
            'description' => 'A test travel package',
            'price' => 25000,
            'duration' => '7 days',
            'departure_cities' => json_encode(['Ahmedabad']),
            'is_featured' => true,
            'destination_id' => $this->destination->id,
            'agency_id' => $this->agency->id,
        ];

        $response = $this->actingAs($this->admin)->post('/admin/packages', $packageData);
        
        $this->assertDatabaseHas('packages', [
            'name' => 'Test Package',
            'price' => 25000,
        ]);
    }

    /**
     * Test admin can update package
     */
    public function test_admin_can_update_package(): void
    {
        $package = Package::factory()->create([
            'agency_id' => $this->agency->id,
            'destination_id' => $this->destination->id,
        ]);

        $updateData = [
            'name' => 'Updated Package',
            'description' => 'Updated description',
            'price' => 30000,
            'duration' => '10 days',
        ];

        $response = $this->actingAs($this->admin)
            ->put("/admin/packages/{$package->id}", $updateData);

        $response->assertRedirect();
        $this->assertDatabaseHas('packages', [
            'id' => $package->id,
            'name' => 'Updated Package',
        ]);
    }

    /**
     * Test admin can delete package
     */
    public function test_admin_can_delete_package(): void
    {
        $package = Package::factory()->create([
            'agency_id' => $this->agency->id,
            'destination_id' => $this->destination->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->delete("/admin/packages/{$package->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('packages', ['id' => $package->id]);
    }

    /**
     * Test admin can view package details
     */
    public function test_admin_can_view_package_details(): void
    {
        $package = Package::factory()->create([
            'agency_id' => $this->agency->id,
            'destination_id' => $this->destination->id,
        ]);

        $response = $this->actingAs($this->admin)
            ->get("/admin/packages/{$package->id}");

        $response->assertStatus(200);
    }

    /**
     * Test package requires valid price
     */
    public function test_package_requires_valid_price(): void
    {
        $packageData = [
            'name' => 'Test Package',
            'description' => 'A test travel package',
            'price' => -100,
            'duration' => '7 days',
            'destination_id' => $this->destination->id,
            'agency_id' => $this->agency->id,
        ];

        $response = $this->actingAs($this->admin)->post('/admin/packages', $packageData);
        $response->assertSessionHasErrors('price');
    }
}
