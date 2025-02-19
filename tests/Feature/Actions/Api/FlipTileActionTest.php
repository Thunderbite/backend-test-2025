<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Frontend;

use App\Actions\Api\FlipTileAction;
use App\Enums\GameStatus;
use App\Enums\Segment;
use App\Models\Game;
use App\Models\GameMove;
use App\Models\Prize;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

final class FlipTileActionTest extends TestCase
{
    public function test_handle_fails_with_invalid_game_id()
    {
        $this->expectException(ModelNotFoundException::class);
        (new FlipTileAction)->handle(0, 1);

        $this->assertDatabaseMissing('game_moves', ['game_id' => 0]);
    }

    public function test_handle_sets_out_of_prizes_for_campaign_with_no_prizes()
    {
        $game = Game::factory()->create();
        Prize::whereCampaignId($game->campaign_id)->delete();

        $response = (new FlipTileAction)->handle($game->id, 1);
        $this->assertEquals(FlipTileAction::MESSAGE_OUT_OF_PRIZES, $response['message']);

        $this->assertDatabaseMissing('game_moves', ['game_id' => 0]);
    }

    public function test_handle_sets_out_of_prizes_for_prize_with_different_segment()
    {
        $game = Game::factory()->create(['segment' => Segment::HIGH]);
        Prize::factory()->create([
            'campaign_id' => $game->campaign_id,
            'description' => 'Prize',
            'segment' => Segment::LOW,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDay(),
        ]);

        $response = (new FlipTileAction)->handle($game->id, 1);
        $this->assertEquals(FlipTileAction::MESSAGE_OUT_OF_PRIZES, $response['message']);

        $this->assertDatabaseMissing('game_moves', ['game_id' => 0]);
    }

    public function test_handle_sets_out_of_prizes_for_prize_with_invalid_starts_at()
    {
        $game = Game::factory()->create(['segment' => Segment::LOW]);
        Prize::factory()->create([
            'campaign_id' => $game->campaign_id,
            'description' => 'Prize',
            'segment' => Segment::LOW,
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addDay(),
        ]);

        $response = (new FlipTileAction)->handle($game->id, 1);
        $this->assertEquals(FlipTileAction::MESSAGE_OUT_OF_PRIZES, $response['message']);

        $this->assertDatabaseMissing('game_moves', ['game_id' => 0]);
    }

    public function test_handle_sets_out_of_prizes_for_prize_with_invalid_ends_at()
    {
        $game = Game::factory()->create(['segment' => Segment::LOW]);
        Prize::factory()->create([
            'campaign_id' => $game->campaign_id,
            'description' => 'Prize',
            'segment' => Segment::LOW,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->subDay(),
        ]);

        $response = (new FlipTileAction)->handle($game->id, 1);
        $this->assertEquals(FlipTileAction::MESSAGE_OUT_OF_PRIZES, $response['message']);

        $this->assertDatabaseMissing('game_moves', ['game_id' => 0]);
    }

    public function test_handle_sets_out_of_prizes_for_prize_with_daily_volume_already_maxed_out()
    {
        $game = Game::factory()->create(['segment' => Segment::LOW]);
        $prize = Prize::factory()->create([
            'campaign_id' => $game->campaign_id,
            'description' => 'Prize',
            'segment' => Segment::LOW,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDay(),
            'daily_volume' => 1,
        ]);

        Game::factory()->create(['won_prize_id' => $prize->id, 'won_at' => now()]);

        $response = (new FlipTileAction)->handle($game->id, 1);
        $this->assertEquals(FlipTileAction::MESSAGE_OUT_OF_PRIZES, $response['message']);

        $this->assertDatabaseMissing('game_moves', ['game_id' => 0]);
    }

    public function test_handle_marks_game_as_revealed_on_first_move()
    {
        $game = Game::factory()->create(['segment' => Segment::LOW]);
        Prize::factory()->create([
            'campaign_id' => $game->campaign_id,
            'description' => 'Prize',
            'segment' => Segment::LOW,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDay(),
        ]);

        (new FlipTileAction)->handle($game->id, 1);
        $this->assertNotNull($game->fresh()->revealed_at);
    }

    public function test_handle_marks_game_as_won()
    {
        config()->set('game.minimum-matching-tiles-to-win', 3);

        $game = Game::factory()->create(['segment' => Segment::LOW]);
        $prize = Prize::factory()->create([
            'campaign_id' => $game->campaign_id,
            'description' => 'Prize',
            'segment' => Segment::LOW,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDay(),
            'image_src' => 'prizes/1.png',
        ]);

        GameMove::create([
            'game_id' => $game->id,
            'prize_id' => $prize->id,
            'index' => 1,
        ]);

        GameMove::create([
            'game_id' => $game->id,
            'prize_id' => $prize->id,
            'index' => 2,
        ]);

        $response = (new FlipTileAction)->handle($game->id, 3);

        $this->assertEquals(['tileImage' => asset('prizes/1.png'), 'message' => $prize->description], $response);
        $this->assertEquals(GameStatus::WON, $game->fresh()->status);
        $this->assertNotNull($game->fresh()->won_at);
        $this->assertDatabaseCount('game_moves', 3);
    }

    public function test_handle_marks_game_as_lost()
    {
        config()->set('game.maximum-game-moves-before-loss', 1);

        $game = Game::factory()->create(['segment' => Segment::LOW]);
        $prize = Prize::factory()->create([
            'campaign_id' => $game->campaign_id,
            'description' => 'Prize',
            'segment' => Segment::LOW,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDay(),
        ]);

        GameMove::create([
            'game_id' => $game->id,
            'prize_id' => Prize::factory()->create()->id,
            'index' => 1,
        ]);

        (new FlipTileAction)->handle($game->id, 3);

        $this->assertEquals(GameStatus::LOST, $game->fresh()->status);
        $this->assertNull($game->fresh()->won_at);
    }
}
