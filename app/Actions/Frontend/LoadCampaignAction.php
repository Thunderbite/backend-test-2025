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

        $message = match(true) {
            $campaign->hasNotStarted() => 'This Campaign has not started yet! Please check back later 😀',
            $campaign->hasEnded() => 'This Campaign has ended. Please select another Campaign to play 🥲',
            default => null
        };

        return json_encode([
            'apiPath' => '/api/flip',
            'gameId' => $game->id,
            'revealedTiles' => [],
            'message' => $message
		]);
	}
}
