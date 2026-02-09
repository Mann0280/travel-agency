<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Agency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAgencyManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    /**
     * Test admin can view agency list
     */
    public function test_admin_can_view_agency_list(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/agencies');
        $response->assertStatus(200);
    }

    /**
     * Test non-admin cannot access agency list
     */
    public function test_non_admin_cannot_access_agency_list(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        
        $response = $this->actingAs($user)->get('/admin/agencies');
        $response->assertStatus(403);
    }

    /**
     * Test admin can create agency
     */
    public function test_admin_can_create_agency(): void
    {
        $agencyData = [
            'name' => 'Test Agency',
            'description' => 'A test travel agency',
            'email' => 'test@agency.com',
            'phone' => '+91-1234567890',
            'address' => 'Test City',
            'logo' => 'agency.jpg',
        ];

        $response = $this->actingAs($this->admin)->post('/admin/agencies', $agencyData);
        
        $this->assertDatabaseHas('agencies', [
            'name' => 'Test Agency',
            'email' => 'test@agency.com',
        ]);
    }

    /**
     * Test admin can verify agency
     */
    public function test_admin_can_verify_agency(): void
    {
        $user = User::factory()->create(['role' => 'agency']);
        $agency = Agency::factory()->create([
            'user_id' => $user->id,
            'is_verified' => false,
        ]);

        $response = $this->actingAs($this->admin)
            ->post("/admin/agencies/{$agency->id}/verify");

        $response->assertRedirect();
        $this->assertTrue($agency->fresh()->is_verified);
    }

    /**
     * Test admin can delete agency
     */
    public function test_admin_can_delete_agency(): void
    {
        $user = User::factory()->create(['role' => 'agency']);
        $agency = Agency::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($this->admin)
            ->delete("/admin/agencies/{$agency->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('agencies', ['id' => $agency->id]);
    }

    /**
     * Test admin can view agency details
     */
    public function test_admin_can_view_agency_details(): void
    {
        $user = User::factory()->create(['role' => 'agency']);
        $agency = Agency::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($this->admin)
            ->get("/admin/agencies/{$agency->id}");

        $response->assertStatus(200);
    }

    /**
     * Test admin can reject agency
     */
    public function test_admin_can_reject_agency(): void
    {
        $user = User::factory()->create(['role' => 'agency']);
        $agency = Agency::factory()->create([
            'user_id' => $user->id,
            'is_verified' => false,
        ]);

        $response = $this->actingAs($this->admin)
            ->post("/admin/agencies/{$agency->id}/reject");

        $response->assertRedirect();
    }
}
