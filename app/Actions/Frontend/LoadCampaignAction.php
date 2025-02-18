<?php

namespace App\Actions\Frontend;

use App\Enums\GameStatus;
use App\Models\Campaign;
use App\Models\Game;

class LoadCampaignAction
{
	public function handle(Campaign $campaign, string $account, string $segment): string
	{
		$game = Game::firstOrCreate([
            'campaign_id' => $campaign->id,
            'account' => $account,
			'segment' => $segment,
            'status' => GameStatus::ACTIVE
        ]);

        return json_encode([
            'apiPath' => '/api/flip',
            'gameId' => $game->id,
            'revealedTiles' => [],
            'message' => null
		]);
	}
}
