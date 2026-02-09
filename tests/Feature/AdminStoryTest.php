<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Story;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminStoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_story_without_user_id()
    {
        Storage::fake('public');

        $admin = Admin::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->post(route('admin.stories.store'), [
                'destination' => 'Test Destination',
                'date' => now()->format('Y-m-d'),
                'image' => UploadedFile::fake()->create('story.jpg', 100),
                'is_active' => true,
            ]);

        $response->assertRedirect(route('admin.stories.index'));
        $response->assertSessionHas('success', 'Story added successfully.');

        $this->assertDatabaseHas('stories', [
            'destination' => 'Test Destination',
            'user_id' => null,
            'title' => 'Test Destination',
        ]);
    }
}
