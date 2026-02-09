<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Package;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReviewManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;
    protected $package;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create Admin
        $this->admin = Admin::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password')
        ]);
        
        // Create User and Package for Reviews
        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'user@test.com',
            'password' => bcrypt('password')
        ]);


        // Create Agency
        $agency = \App\Models\Agency::create([
            'name' => 'Test Agency',
            'description' => 'Test Description',
            'email' => 'agency@test.com',
            'password' => bcrypt('password'),
            'phone' => '1234567890',
            'address' => 'Test Address',
            'is_verified' => true,
        ]);

        // Create Destination
        $destination = \App\Models\Destination::create([
            'name' => 'Test Destination',
            'description' => 'Test Description',
            'location' => 'Test Location',
        ]);

        $this->package = Package::create([
            'name' => 'Test Package',
            'description' => 'Test Description',
            'price' => 1000,
            'duration' => '5 days',
            'agency_id' => $agency->id,
            'destination_id' => $destination->id,
            'departure_cities' => ['Mumbai', 'Delhi'],
            'travel_date' => now()->addDays(30),
            'is_approved' => true,
        ]);
    }

    public function test_admin_can_view_reviews_page()
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.reviews.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.reviews.index');
    }

    public function test_admin_can_see_reviews_list()
    {
        $review = Review::create([
            'user_id' => $this->user->id,
            'package_id' => $this->package->id,
            'rating' => 5,
            'comment' => 'Great trip!',
            'title' => 'Awesome',
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.reviews.index'));
        
        $response->assertSee('Great trip!');
        $response->assertSee('Awesome');
    }

    public function test_admin_can_update_review_status()
    {
        $review = Review::create([
            'user_id' => $this->user->id,
            'package_id' => $this->package->id,
            'rating' => 5,
            'comment' => 'Great trip!',
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.reviews.status'), [
            'review_id' => $review->id,
            'status' => 'approved'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'status' => 'approved'
        ]);
    }

    public function test_admin_can_reply_to_review()
    {
        $review = Review::create([
            'user_id' => $this->user->id,
            'package_id' => $this->package->id,
            'rating' => 5,
            'comment' => 'Great trip!',
        ]);

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.reviews.reply'), [
            'review_id' => $review->id,
            'reply_text' => 'Thank you!',
            'reply_by' => 'Admin Team'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('review_replies', [
            'review_id' => $review->id,
            'reply_text' => 'Thank you!',
            'reply_by' => 'Admin Team'
        ]);
    }

    public function test_admin_can_toggle_featured_status()
    {
        $review = Review::create([
            'user_id' => $this->user->id,
            'package_id' => $this->package->id,
            'rating' => 5,
            'comment' => 'Great trip!',
            'featured' => false
        ]);

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.reviews.toggleFeatured'), [
            'review_id' => $review->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'featured' => true
        ]);
    }

    public function test_admin_can_delete_review()
    {
        $review = Review::create([
            'user_id' => $this->user->id,
            'package_id' => $this->package->id,
            'rating' => 5,
            'comment' => 'Great trip!',
        ]);

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.reviews.destroy'), [
            'review_id' => $review->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }
}
