<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test dashboard page loads successfully.
     */
    public function test_dashboard_page_loads_successfully(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
        $response->assertViewHas('unpaid_month');
        $response->assertViewHas('unpaid_year');
    }

    /**
     * Test dashboard AJAX request for unpaid players list.
     */
    public function test_dashboard_ajax_unpaid_players_filter(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson(route('dashboard', [
            'unpaid_month' => 6,
            'unpaid_year' => 2026
        ]), [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data'
        ]);
    }
}
