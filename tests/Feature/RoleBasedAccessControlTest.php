<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Package;
use App\Models\Agency;
use App\Models\Destination;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleBasedAccessControlTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $regularUser;
    protected User $agencyUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->regularUser = User::factory()->create(['role' => 'user']);
        $this->agencyUser = User::factory()->create(['role' => 'agency']);
    }

    /**
     * Test admin can access admin dashboard
     */
    public function test_admin_can_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/dashboard');
        $response->assertStatus(200);
    }

    /**
     * Test regular user cannot access admin dashboard
     */
    public function test_regular_user_cannot_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->regularUser)->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    /**
     * Test agency user cannot access admin dashboard
     */
    public function test_agency_user_cannot_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->agencyUser)->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    /**
     * Test admin login redirects to admin dashboard
     */
    public function test_admin_login_redirects_to_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => $this->admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
    }

    /**
     * Test regular user login redirects to account page
     */
    public function test_regular_user_login_redirects_to_account(): void
    {
        $response = $this->post('/login', [
            'email' => $this->regularUser->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('account'));
    }

    /**
     * Test user cannot access other users account
     */
    public function test_user_cannot_access_other_users_account(): void
    {
        $otherUser = User::factory()->create(['role' => 'user']);
        
        $response = $this->actingAs($this->regularUser)->get("/account/{$otherUser->id}");
        $response->assertStatus(403);
    }

    /**
     * Test regular user cannot manage packages
     */
    public function test_regular_user_cannot_manage_packages(): void
    {
        $response = $this->actingAs($this->regularUser)->get('/admin/packages');
        $response->assertStatus(403);

        $response = $this->actingAs($this->regularUser)->get('/admin/packages/create');
        $response->assertStatus(403);
    }

    /**
     * Test regular user cannot manage agencies
     */
    public function test_regular_user_cannot_manage_agencies(): void
    {
        $response = $this->actingAs($this->regularUser)->get('/admin/agencies');
        $response->assertStatus(403);
    }

    /**
     * Test unauthenticated user cannot access protected routes
     */
    public function test_unauthenticated_user_cannot_access_protected_routes(): void
    {
        $response = $this->get('/account');
        $response->assertRedirect(route('login'));

        $response = $this->post('/logout');
        $response->assertRedirect(route('login'));
    }

    /**
     * Test admin can manage all resources
     */
    public function test_admin_can_manage_all_resources(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/packages');
        $response->assertStatus(200);

        $response = $this->actingAs($this->admin)->get('/admin/agencies');
        $response->assertStatus(200);
    }
}
