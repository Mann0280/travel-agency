<?php

namespace Tests\Feature;

use App\Models\Agency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgencyLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_agency_can_login_and_access_dashboard()
    {
        $agency = Agency::create([
            'name' => 'Test Agency',
            'email' => 'agency@test.com',
            'password' => bcrypt('password123'),
            'is_verified' => true,
        ]);

        $response = $this->post(route('agency.login.submit'), [
            'email' => 'agency@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('agency.dashboard'));
        $this->assertAuthenticatedAs($agency, 'agency');

        $response = $this->actingAs($agency, 'agency')->get(route('agency.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Dashboard');
    }

    public function test_agency_cannot_access_dashboard_without_login()
    {
        $response = $this->get(route('agency.dashboard'));
        $response->assertRedirect(route('agency.login'));
    }

    public function test_agency_can_logout_and_redirect_to_login()
    {
        $agency = Agency::create([
            'name' => 'Test Agency',
            'email' => 'logout@test.com',
            'password' => bcrypt('password123'),
            'is_verified' => true,
        ]);

        $response = $this->actingAs($agency, 'agency')->post(route('agency.logout'));

        $response->assertRedirect(route('agency.login'));
        $this->assertGuest('agency');
    }
}
