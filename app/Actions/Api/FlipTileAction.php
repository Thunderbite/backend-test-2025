<?php

namespace App\Actions\Api;

use App\Models\Game;

class FlipTileAction
{
	public function handle(int $gameId, int $index): array
	{
        $game = Game::findOrFail($gameId)->withCount('moves')->first();
		$movesCount = (int) $game->moves_count;

         $nextPrize = $game
		 	->campaign
			->prizes()
			->whereSegment($game->segment)
			->orderByRaw('-LOG(1.0 - RAND()) / prizes.weight')
			->first();

		if ($movesCount === 0) {
			// Should be based on the timezone of the campaign
			$game->update(['revealed_at' => now()]);
		}

		// What happens when $nextPrize is empty?

		$game->moves()->create(['prize_id' => $nextPrize->id, 'index' => $index]);

		$response = ['tileImage' => asset($nextPrize->image_src)];
        if ((int) $game->moves_count >= (int) config('game.maximum-game-moves-before-loss')) {
            $response['message'] = 'You lost!';
        }

		return $response;
	}
}
