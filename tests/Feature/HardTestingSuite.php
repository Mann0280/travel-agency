<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Agency;
use App\Models\User;
use App\Models\Package;
use App\Models\Destination;
use App\Models\Story;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HardTestingSuite extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Setup base data
        $this->artisan('db:seed');
    }

    /** @test */
    public function phase_1_auth_guard_isolation()
    {
        $admin = Admin::where('email', 'admin@travelagency.com')->first();
        $agency = Agency::where('email', 'agency@zubee.com')->first();
        $user = User::where('email', 'john@example.com')->first();

        // 1. User cannot access Admin Dashboard
        $this->actingAs($user, 'web')->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));

        // 2. User cannot access Agency Dashboard
        $this->actingAs($user, 'web')->get(route('agency.dashboard'))->assertRedirect(route('agency.login'));

        // 3. Agency cannot access Admin Dashboard
        $this->actingAs($agency, 'agency')->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));

        // 4. Admin can access Admin Dashboard
        $this->actingAs($admin, 'admin')->get(route('admin.dashboard'))->assertStatus(200);

        // 5. Agency can access Agency Dashboard
        $this->actingAs($agency, 'agency')->get(route('agency.dashboard'))->assertStatus(200);
    }

    /** @test */
    public function phase_2_agency_package_crud_and_isolation()
    {
        Storage::fake('public');
        $agency1 = Agency::where('email', 'agency@zubee.com')->first();
        $agency2 = Agency::create([
            'name' => 'Competitor Tours',
            'email' => 'other@agency.com',
            'password' => bcrypt('password'),
        ]);

        $destination = Destination::first();

        // Agency 1 creates a package
        $response = $this->actingAs($agency1, 'agency')->post(route('agency.packages.store'), [
            'name' => 'Agency 1 Package',
            'description' => 'Test',
            'price' => 1000,
            'duration' => '3 days',
            'departure_cities' => ['Test City'],
            'travel_date' => now()->addDays(10)->format('Y-m-d'),
            'destination_id' => $destination->id,
            'image' => UploadedFile::fake()->create('pkg.jpg', 100),
        ]);

        $response->assertRedirect(route('agency.packages.index'));
        $package = Package::where('name', 'Agency 1 Package')->first();
        $this->assertEquals($agency1->id, $package->agency_id);

        // Hard Test: Agency 2 tries to Edit Agency 1's package
        $this->actingAs($agency2, 'agency')
             ->get(route('agency.packages.edit', $package))
             ->assertStatus(403); // Assuming policy/middleware handles this

        // Hard Test: Agency 2 tries to Update Agency 1's package
        $this->actingAs($agency2, 'agency')
             ->put(route('agency.packages.update', $package), ['name' => 'Hacked Name'])
             ->assertStatus(403);
    }

    /** @test */
    public function phase_3_admin_stories_system_check()
    {
        Storage::fake('public');
        $admin = Admin::first();

        // Create a story through the specialized controller
        $response = $this->actingAs($admin, 'admin')->post(route('admin.stories.store'), [
            'destination' => 'Testing Land',
            'date' => now()->format('Y-m-d'),
            'is_active' => true,
            'order' => 5,
            'image' => UploadedFile::fake()->create('story.jpg', 100),
        ]);

        $response->assertRedirect(route('admin.stories.index'));
        $this->assertDatabaseHas('stories', [
            'destination' => 'Testing Land',
            'order' => 5,
        ]);
        
        // Verify view rendering for Stories List
        $this->actingAs($admin, 'admin')->get(route('admin.stories.index'))
             ->assertStatus(200)
             ->assertSee('Testing Land');
    }

    /** @test */
    public function phase_4_global_settings_persistence()
    {
        $admin = Admin::first();
        
        // Change site name as admin
        $this->actingAs($admin, 'admin')->post(route('admin.settings.update'), [
            'site_name' => 'HARD TEST CORP',
        ]);

        // Verify across multiple front-end pages
        $this->get('/')->assertSee('HARD TEST CORP');
        $this->get(route('register'))->assertSee('HARD TEST CORP');
    }

    /** @test */
    public function phase_5_search_and_registration()
    {
        // 1. Test Search Logic
        $response = $this->get('/search?from=Ahmedabad&to=Himachal&month=Dec&budget=30000');
        $response->assertStatus(200);

        // 2. Test User Registration
        $response = $this->post(route('register'), [
            'name' => 'New Traveler',
            'email' => 'new@travel.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('account'));
        $this->assertDatabaseHas('users', ['email' => 'new@travel.com']);

        // 3. Verify personalized dashboard
        $this->actingAs(User::where('email', 'new@travel.com')->first())
             ->get(route('account'))
             ->assertStatus(200)
             ->assertSee('New Traveler');
    }

    /** @test */
    public function phase_6_data_integrity_and_referential_stress()
    {
        $admin = Admin::first();
        $destination = Destination::first();
        $package = Package::where('destination_id', $destination->id)->first();

        // 1. Delete a destination that has packages
        $response = $this->actingAs($admin, 'admin')->delete(route('admin.destinations.destroy', $destination));
        
        // Hard Test: Should redirect with error instead of crashing
        $response->assertRedirect(route('admin.destinations.index'));
        $response->assertSessionHas('error', 'Cannot delete destination with active packages.');
        
        // Verify package page still loads safely
        $response = $this->get(route('package.show', $package));
        $this->assertEquals(200, $response->status());

        // 2. Cross-Agency Booking Privacy
        $agency1 = Agency::first();
        $agency2 = Agency::create([
            'name' => 'Privacy Test Agency',
            'email' => 'privacy@test.com',
            'password' => bcrypt('password'),
        ]);

        $user = User::first();
        $booking = \App\Models\Booking::create([
            'user_id' => $user->id,
            'package_id' => $package->id, // This package belongs to agency1
            'travel_date' => now()->addDays(20)->format('Y-m-d'),
            'number_of_travelers' => 2,
            'total_amount' => $package->price * 2,
            'status' => 'pending',
        ]);

        // Agency 2 tries to view Agency 1's booking
        $this->actingAs($agency2, 'agency')
             ->get(route('agency.bookings.show', $booking))
             ->assertStatus(403);
    }

    /** @test */
    public function phase_7_malicious_input_stress()
    {
        $admin = Admin::first();

        // Test with extremely long strings and potential XSS payload
        $longString = str_repeat('STRESS_', 1000);
        $xssPayload = '<script>alert("XSS")</script>';

        $response = $this->actingAs($admin, 'admin')->post(route('admin.destinations.store'), [
            'name' => 'XSS_' . $xssPayload,
            'description' => $longString,
            'location' => 'Malicious City',
            'highlights' => ['High 1', 'High 2'],
        ]);

        $response->assertRedirect(route('admin.destinations.index'));
        
        // Verify that it's stored (and hopefully escaped by Blade on render)
        $this->assertDatabaseHas('destinations', [
            'location' => 'Malicious City',
        ]);

        // Verify that the page renders without crashing
        $this->get(route('admin.destinations.index'))
             ->assertStatus(200);
    }
}
