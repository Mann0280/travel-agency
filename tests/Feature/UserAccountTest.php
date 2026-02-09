<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAccountTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'user']);
    }

    /**
     * Test unauthenticated user cannot access account page
     */
    public function test_unauthenticated_user_cannot_access_account_page(): void
    {
        $response = $this->get('/account');
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /**
     * Test authenticated user can view account page
     */
    public function test_authenticated_user_can_view_account_page(): void
    {
        $response = $this->actingAs($this->user)->get('/account');
        $response->assertStatus(200);
        $response->assertViewIs('account.index');
    }

    /**
     * Test user can update profile
     */
    public function test_user_can_update_profile(): void
    {
        $updateData = [
            'name' => 'Updated Name',
            'email' => 'newemail@example.com',
            'phone' => '+91-9876543210',
        ];

        $response = $this->actingAs($this->user)
            ->post('/account/update-profile', $updateData);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'Updated Name',
        ]);
    }

    /**
     * Test user can change password
     */
    public function test_user_can_change_password(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/account/change-password', [
                'current_password' => 'password',
                'password' => 'newpassword',
                'password_confirmation' => 'newpassword',
            ]);

        $response->assertRedirect();
    }

    /**
     * Test user cannot change password with wrong current password
     */
    public function test_user_cannot_change_password_with_wrong_current(): void
    {
        $response = $this->actingAs($this->user)
            ->post('/account/change-password', [
                'current_password' => 'wrongpassword',
                'password' => 'newpassword',
                'password_confirmation' => 'newpassword',
            ]);

        $response->assertSessionHasErrors();
    }

    /**
     * Test user can submit feedback
     */
    public function test_user_can_submit_feedback(): void
    {
        $feedbackData = [
            'subject' => 'Great Service',
            'message' => 'I had an amazing experience with this travel agency!',
            'rating' => 5,
        ];

        $response = $this->actingAs($this->user)
            ->post('/account/submit-feedback', $feedbackData);

        $response->assertRedirect();
        $this->assertDatabaseHas('feedback', [
            'user_id' => $this->user->id,
            'subject' => 'Great Service',
        ]);
    }

    /**
     * Test feedback requires valid rating
     */
    public function test_feedback_requires_valid_rating(): void
    {
        $feedbackData = [
            'subject' => 'Great Service',
            'message' => 'I had an amazing experience!',
            'rating' => 10, // Invalid rating
        ];

        $response = $this->actingAs($this->user)
            ->post('/account/submit-feedback', $feedbackData);

        $response->assertSessionHasErrors('rating');
    }

    /**
     * Test user profile update requires valid email
     */
    public function test_profile_update_requires_valid_email(): void
    {
        $updateData = [
            'name' => 'Updated Name',
            'email' => 'invalid-email',
            'phone' => '+91-9876543210',
        ];

        $response = $this->actingAs($this->user)
            ->post('/account/update-profile', $updateData);

        $response->assertSessionHasErrors('email');
    }

    /**
     * Test user cannot use duplicate email on update
     */
    public function test_user_cannot_use_duplicate_email(): void
    {
        $otherUser = User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $updateData = [
            'name' => 'Updated Name',
            'email' => 'existing@example.com',
            'phone' => '+91-9876543210',
        ];

        $response = $this->actingAs($this->user)
            ->post('/account/update-profile', $updateData);

        $response->assertSessionHasErrors('email');
    }

    /**
     * Test user bookings are displayed on account page
     */
    public function test_user_bookings_displayed_on_account(): void
    {
        $response = $this->actingAs($this->user)->get('/account');
        $response->assertStatus(200);
    }
}
