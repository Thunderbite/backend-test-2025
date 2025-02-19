<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Frontend;

use App\Actions\Frontend\LoadCampaignAction;
use App\Enums\GameStatus;
use App\Enums\Segment;
use App\Models\Campaign;
use App\Models\Game;
use App\Models\GameMove;
use App\Models\Prize;
use Tests\TestCase;

final class LoadCampaignActionTest extends TestCase
{
    public function test_handle_creates_game()
    {
        $campaign = Campaign::factory()->create();
        $account = 'completely-new-account';
        Game::whereAccount($account)->delete();

        (new LoadCampaignAction)->handle($campaign, $account, Segment::LOW);

        $this->assertDatabaseCount('games', 1);
        $this->assertDatabaseHas('games', [
            'campaign_id' => $campaign->id,
            'account' => $account,
            'segment' => Segment::LOW,
            'status' => GameStatus::ACTIVE,
        ]);
    }

    public function test_handle_gets_existing_game()
    {
        $campaign = Campaign::factory()->create();
        $account = 'completely-new-account';

        Game::create([
            'campaign_id' => $campaign->id,
            'account' => $account,
            'segment' => Segment::LOW,
            'status' => GameStatus::ACTIVE,
        ]);

        (new LoadCampaignAction)->handle($campaign, $account, Segment::LOW);

        $this->assertDatabaseCount('games', 1);
    }

    public function test_handle_returns_message_when_campaign_has_not_started_yet()
    {
        $campaign = Campaign::factory()->create([
            'starts_at' => now()->addYear(),
        ]);

        $response = (new LoadCampaignAction)->handle($campaign, 'account', Segment::LOW);

        $this->assertStringContainsString(LoadCampaignAction::ERROR_CAMPAIGN_HAS_NOT_STARTED, $response);
    }

    public function test_handle_returns_message_when_campaign_has_ended()
    {
        $campaign = Campaign::factory()->create([
            'ends_at' => now()->subYear(),
        ]);

        $response = (new LoadCampaignAction)->handle($campaign, 'account', Segment::LOW);

        $this->assertStringContainsString(LoadCampaignAction::ERROR_CAMPAIGN_HAS_ENDED, $response);
    }

    public function test_handle_returns_reveled_tiles()
    {
        $campaign = Campaign::factory()->create();
        $prize = Prize::factory()->create(['campaign_id' => $campaign->id, 'image_src' => 'prizes/1.png']);

        $game = Game::create([
            'campaign_id' => $campaign->id,
            'account' => 'account',
            'segment' => Segment::LOW,
            'status' => GameStatus::ACTIVE,
        ]);

        GameMove::create([
            'game_id' => $game->id,
            'prize_id' => $prize->id,
            'index' => 1,
        ]);

        $response = (new LoadCampaignAction)->handle($campaign, 'account', Segment::LOW);

        $this->assertEquals(json_encode([
            'apiPath' => '/api/flip',
            'gameId' => $game->id,
            'reveledTiles' => [
                ['index' => 1, 'image' => asset('prizes/1.png')],
            ],
            'message' => null,
        ]), $response);
    }
}
