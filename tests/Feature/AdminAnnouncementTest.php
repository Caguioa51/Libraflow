<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\SystemSetting;

class AdminAnnouncementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_announcement_and_public_page_shows_it()
    {
        // create an admin
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.announcements.store'), ['announcement' => 'Test announcement from feature test']);

        $this->assertEquals('Test announcement from feature test', SystemSetting::get('library_announcement'));

    // dashboard should display it for authenticated users
    $response = $this->get('/dashboard');
    $response->assertStatus(200);
    $response->assertSee('Test announcement from feature test');
    }
}
