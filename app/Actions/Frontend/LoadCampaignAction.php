<?php

declare(strict_types=1);

namespace App\Actions\Frontend;

use App\Enums\GameStatus;
use App\Enums\Segment;
use App\Models\Campaign;
use App\Models\Game;

final class LoadCampaignAction
{
    public const ERROR_CAMPAIGN_HAS_NOT_STARTED = 'Campaign has not started yet';

    public const ERROR_CAMPAIGN_HAS_ENDED = 'Campaign has ended';

    public function handle(Campaign $campaign, string $account, Segment $segment): string
    {
        $game = Game::firstOrCreate([
            'campaign_id' => $campaign->id,
            'account' => $account,
            'segment' => $segment->value,
            'status' => GameStatus::ACTIVE,
        ]);

        $message = match (true) {
            $campaign->hasNotStarted() => self::ERROR_CAMPAIGN_HAS_NOT_STARTED,
            $campaign->hasEnded() => self::ERROR_CAMPAIGN_HAS_ENDED,
            default => null
        };

        $tiles = $game->moves()->with('prize')->get()->map(fn ($move) => [
            'index' => $move->index,
            'image' => $move->prize->image_url,
        ]);

        return json_encode([
            'apiPath' => '/api/flip',
            'gameId' => $game->id,
            'reveledTiles' => $tiles,
            'message' => $message,
        ]);
    }
}
