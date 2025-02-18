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
            $campaign->hasNotStarted() => 'Campaign has not started yet',
            $campaign->hasEnded() => 'Campaign has ended',
            default => null
        };

        $tiles = $game->moves()->with('prize')->get()->map(fn ($move) => [
            'index' => $move->index,
            'image' => $move->prize->image_url
        ]);

        return json_encode([
            'apiPath' => '/api/flip',
            'gameId' => $game->id,
            'revealedTiles' => $tiles,
            'message' => $message
		]);
	}
}
