<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Fixture;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FixtureTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_fixtures_list_is_accessible(): void
    {
        $response = $this->get('/fixtures');
        $response->assertStatus(200);
    }

    public function test_public_fixture_show_is_accessible(): void
    {
        $fixture = Fixture::create([
            'home_team' => 'Brazil',
            'away_team' => 'Germany',
            'match_time' => now()->addDays(2),
            'status' => 'upcoming',
        ]);

        $response = $this->get("/fixtures/{$fixture->id}");
        $response->assertStatus(200);
        $response->assertSee('Brazil');
        $response->assertSee('Germany');
    }

    public function test_admin_can_manage_fixtures(): void
    {
        $admin = Admin::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
        ]);

        // Admin can see index
        $response = $this->actingAs($admin, 'admin')->get('/admin/fixtures');
        $response->assertStatus(200);

        // Admin can create fixture
        $response = $this->actingAs($admin, 'admin')->post('/admin/fixtures', [
            'home_team' => 'Argentina',
            'away_team' => 'France',
            'match_time' => '2026-07-19T16:00',
            'status' => 'live',
        ]);
        $response->assertRedirect('/admin/fixtures');
        $this->assertDatabaseHas('fixtures', [
            'home_team' => 'Argentina',
            'away_team' => 'France',
        ]);

        $fixture = Fixture::where('home_team', 'Argentina')->first();

        // Admin can edit fixture
        $response = $this->actingAs($admin, 'admin')->put("/admin/fixtures/{$fixture->id}", [
            'home_team' => 'Argentina',
            'away_team' => 'France',
            'match_time' => '2026-07-19T16:00',
            'status' => 'finished',
            'home_score' => 3,
            'away_score' => 3,
        ]);
        $response->assertRedirect('/admin/fixtures');
        $this->assertDatabaseHas('fixtures', [
            'home_score' => 3,
            'status' => 'finished',
        ]);

        // Admin can delete fixture
        $response = $this->actingAs($admin, 'admin')->delete("/admin/fixtures/{$fixture->id}");
        $response->assertRedirect('/admin/fixtures');
        $this->assertDatabaseMissing('fixtures', [
            'id' => $fixture->id,
        ]);
    }
}
