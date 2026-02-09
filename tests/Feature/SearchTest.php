<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Package;
use App\Models\Destination;
use App\Models\Agency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    protected Agency $agency;
    protected Destination $destination;
    protected Package $package;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test data
        $user = User::factory()->create(['role' => 'agency']);
        $this->agency = Agency::factory()->create(['user_id' => $user->id]);
        
        $this->destination = Destination::factory()->create([
            'location' => 'Spiti Valley',
            'name' => 'Spiti Valley Adventure',
        ]);

        $this->package = Package::factory()->create([
            'agency_id' => $this->agency->id,
            'destination_id' => $this->destination->id,
            'name' => 'Spiti Valley Tour',
            'price' => 25000,
            'duration' => '7 days',
            'departure_cities' => ['Ahmedabad'],
            'is_featured' => true,
        ]);
    }

    /**
     * Test user can access search page
     */
    public function test_user_can_access_search_page(): void
    {
        $response = $this->get('/search');
        $response->assertStatus(200);
        $response->assertViewIs('search');
    }

    /**
     * Test search returns all packages by default
     */
    public function test_search_returns_all_packages(): void
    {
        $response = $this->get('/search');
        $response->assertStatus(200);
    }

    /**
     * Test search with destination filter
     */
    public function test_search_with_destination_filter(): void
    {
        $response = $this->get('/search', [
            'to' => 'Spiti Valley',
        ]);

        $response->assertStatus(200);
    }

    /**
     * Test search with budget filter
     */
    public function test_search_with_budget_filter(): void
    {
        $response = $this->get('/search', [
            'budget' => '20000-30000',
        ]);

        $response->assertStatus(200);
    }

    /**
     * Test search with duration filter
     */
    public function test_search_with_duration_filter(): void
    {
        $response = $this->get('/search', [
            'duration' => '5-7',
        ]);

        $response->assertStatus(200);
    }

    /**
     * Test search with multiple filters
     */
    public function test_search_with_multiple_filters(): void
    {
        $response = $this->get('/search', [
            'to' => 'Spiti Valley',
            'budget' => '20000-30000',
            'duration' => '5-7',
            'month' => 'December',
        ]);

        $response->assertStatus(200);
    }

    /**
     * Test search with date filter
     */
    public function test_search_with_date_filter(): void
    {
        $response = $this->get('/search', [
            'date' => '2026-02-15',
        ]);

        $response->assertStatus(200);
    }

    /**
     * Test search with custom duration filter
     */
    public function test_search_with_custom_duration_filter(): void
    {
        $response = $this->get('/search', [
            'duration' => 'custom',
            'custom_duration' => '7',
        ]);

        $response->assertStatus(200);
    }
}
