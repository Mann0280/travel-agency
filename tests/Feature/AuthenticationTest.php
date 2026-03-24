<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user can view login page
     */
    public function test_user_can_view_login_page(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertViewIs('user.auth.login');
    }

    /**
     * Test user can view register page
     */
    public function test_user_can_view_register_page(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertViewIs('user.auth.register');
    }

    /**
     * Test user cannot login with invalid credentials
     */
    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'invalid@example.com',
            'password' => 'invalid_password',
            'agree_terms' => 'on',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }

    /**
     * Test user can login with valid credentials
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
            'agree_terms' => 'on',
        ]);

        $response->assertRedirect(route('account'));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test admin user is redirected to admin dashboard
     */
    public function test_admin_user_redirected_to_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
            'agree_terms' => 'on',
        ]);

        $response->assertRedirect(route('account'));
        $this->assertAuthenticatedAs($admin);
    }

    /**
     * Test regular user is redirected to account page
     */
    public function test_regular_user_redirected_to_account(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $response = $this->post('/login', [
            'email' => 'user@example.com',
            'password' => 'password',
            'agree_terms' => 'on',
        ]);

        $response->assertRedirect(route('account'));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test remember me functionality
     */
    public function test_remember_me_functionality(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
            'remember_me' => true,
            'agree_terms' => 'on',
        ]);

        $response->assertRedirect(route('account'));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test user can register
     */
    public function test_user_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'newuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'phone' => '1234567890',
            'agree_terms' => 'on',
        ]);

        $response->assertRedirect(route('account'));
        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
            'name' => 'Test User',
            'role' => 'user',
        ]);
    }

    /**
     * Test registration requires valid email
     */
    public function test_registration_requires_valid_email(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'invalid-email',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /**
     * Test registration prevents duplicate email
     */
    public function test_registration_prevents_duplicate_email(): void
    {
        User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'existing@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /**
     * Test user can logout
     */
    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect(route('home'));
        $this->assertGuest();
    }

    /**
     * Test authenticated user cannot access login page
     */
    public function test_authenticated_user_cannot_access_login_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/login');

        $response->assertRedirect(route('account'));
    }

    /**
     * Test authenticated user cannot access register page
     */
    public function test_authenticated_user_cannot_access_register_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/register');

        $response->assertRedirect(route('account'));
    }
}
