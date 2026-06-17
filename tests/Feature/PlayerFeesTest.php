<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Batch;
use App\Models\Sport;
use App\Models\Level;
use App\Models\PlayerFee;
use Carbon\Carbon;

class PlayerFeesTest extends TestCase
{
    use RefreshDatabase;

    public function test_player_fees_month_and_year_filters(): void
    {
        $user = User::factory()->create();

        // Create a player
        $player = User::factory()->create(['role' => 'player']);

        // Create sport, level, batch
        $sport = Sport::create(['name' => 'Cricket']);
        $level = Level::create(['name' => 'Pro']);
        $batch = Batch::create([
            'name' => 'Cricket Pro Batch',
            'sport_id' => $sport->id,
            'level_id' => $level->id,
            'capacity' => 10,
            'status' => 'active',
            'start_time' => '06:00:00',
            'end_time' => '08:00:00',
        ]);

        // Create a fee record for May 2026
        $mayFee = PlayerFee::create([
            'player_id' => $player->id,
            'batch_id' => $batch->id,
            'start_date' => '2026-05-01',
            'end_date' => '2026-05-31',
            'sub_totalamount' => 4500,
            'total_amt' => 4500,
            'payment_type' => 'card',
            'status' => 'paid',
        ]);

        // Create a fee record for June 2026
        $juneFee = PlayerFee::create([
            'player_id' => $player->id,
            'batch_id' => $batch->id,
            'start_date' => '2026-06-01',
            'end_date' => '2026-06-30',
            'sub_totalamount' => 4500,
            'total_amt' => 4500,
            'payment_type' => 'card',
            'status' => 'paid',
        ]);

        // Request with month=6 and year=2026
        $response = $this->actingAs($user)->getJson(route('player-fees.index', [
            'month' => 6,
            'year' => 2026
        ]), [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $response->assertStatus(200);
        $data = $response->json('data');

        // We should only get 1 record (June fee)
        $this->assertCount(1, $data);
        $this->assertEquals($juneFee->id, $data[0]['id']);
    }
}
