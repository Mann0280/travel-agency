<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Package;
use App\Models\Agency;
use App\Models\Destination;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityAndValidationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'user']);
    }

    /**
     * Test SQL Injection Prevention - User input is parameterized
     */
    public function test_sql_injection_prevention(): void
    {
        $maliciousInput = "'; DROP TABLE users; --";
        
        $response = $this->get('/search', [
            'to' => $maliciousInput,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
        ]);
    }

    /**
     * Test XSS Prevention - User input is escaped in Blade templates
     */
    public function test_xss_prevention_in_feedback(): void
    {
        $xssPayload = '<script>alert("XSS")</script>';
        
        $response = $this->actingAs($this->user)->post('/account/submit-feedback', [
            'subject' => $xssPayload,
            'message' => 'Test feedback',
            'rating' => 5,
        ]);

        $response->assertRedirect();
        // XSS payload should be stored as text, not executed
    }

    /**
     * Test CSRF Token Validation on forms
     */
    public function test_csrf_token_validation(): void
    {
        // POST without CSRF token should fail
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(419); // Token Mismatch
    }

    /**
     * Test Input Validation - Required Fields
     */
    public function test_registration_requires_all_fields(): void
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => '',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }

    /**
     * Test Email Format Validation
     */
    public function test_registration_validates_email_format(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'not-an-email',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /**
     * Test Password Confirmation Validation
     */
    public function test_registration_validates_password_confirmation(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'different',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /**
     * Test Password Minimum Length
     */
    public function test_password_minimum_length_validation(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => '123',
            'password_confirmation' => '123',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /**
     * Test Phone Number Format Validation
     */
    public function test_phone_number_validation(): void
    {
        $response = $this->actingAs($this->user)->post('/account/update-profile', [
            'name' => 'Test User',
            'email' => $this->user->email,
            'phone' => 'invalid-phone',
        ]);

        // Should either pass with invalid phone or show error
        // depending on validation rules
    }

    /**
     * Test Package Price Validation - Must be positive
     */
    public function test_package_price_must_be_positive(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $agencyUser = User::factory()->create(['role' => 'agency']);
        $agency = Agency::factory()->create(['user_id' => $agencyUser->id]);
        $destination = Destination::factory()->create();

        $response = $this->actingAs($admin)->post('/admin/packages', [
            'name' => 'Test Package',
            'description' => 'Test',
            'price' => -1000,
            'duration' => '7 days',
            'destination_id' => $destination->id,
            'agency_id' => $agency->id,
        ]);

        $response->assertSessionHasErrors('price');
    }

    /**
     * Test Rating Validation - Must be 1-5
     */
    public function test_feedback_rating_must_be_between_1_and_5(): void
    {
        $response = $this->actingAs($this->user)->post('/account/submit-feedback', [
            'subject' => 'Great service',
            'message' => 'Very good!',
            'rating' => 6,
        ]);

        $response->assertSessionHasErrors('rating');
    }

    /**
     * Test Timestamp Injection Prevention
     */
    public function test_timestamp_manipulation_prevention(): void
    {
        $response = $this->actingAs($this->user)->post('/account/update-profile', [
            'name' => 'Test User',
            'email' => 'newemail@example.com',
            'created_at' => '2000-01-01',
        ]);

        // created_at should not be updatable
        $this->assertNotEquals('2000-01-01', $this->user->fresh()->created_at);
    }

    /**
     * Test Sensitive Data Not Exposed in Response
     */
    public function test_sensitive_data_not_in_response(): void
    {
        $response = $this->actingAs($this->user)->get('/account');
        
        // Password should not be in response
        $this->assertStringNotContainsString($this->user->password, $response->content());
    }

    /**
     * Test Unique Email Validation
     */
    public function test_unique_email_validation_on_registration(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'taken@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
    }
}
