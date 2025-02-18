<?php

namespace App\Actions\Api;

use App\Enums\GameStatus;
use App\Models\Game;
use App\Models\Prize;
use Illuminate\Support\Traits\Conditionable;

class FlipTileAction
{
	use Conditionable;

	private Game $game;
	private Prize $nextPrize;
	private ?string $message = null;

	public function handle(int $gameId, int $index): array
	{
		return $this
			->setGame($gameId)
			->setNextPrize()
			->when($this->game->moves_count === 0, fn () => $this->markGameAsRevealed())
			->createMove($index)
			->when($this->game->hasExceededAllowedMovesBeforeLoss(), fn () => $this->markAsLost())
			->done();
	}

	private function setGame(int $gameId): self
	{
		$this->game = Game::findOrFail($gameId)->withCount('moves')->first();

		return $this;
	}

	private function setNextPrize(): self
	{
		$this->nextPrize = $this->game
		 	->campaign
			->prizes()
			->whereSegment($this->game->segment)
			->orderByRaw('-LOG(1.0 - RAND()) / prizes.weight')
			->first();

		// What happens when $nextPrize is empty?
		return $this;
	}

	private function markGameAsRevealed(): self
	{
		$this->game->update(['revealed_at' => now()]);

		return $this;
	}

	private function createMove(int $index): self
	{
		$this->game->moves()->create(['prize_id' => $this->nextPrize->id, 'index' => $index]);

		return $this;
	}

	private function markAsLost(): self
	{
		$this->game->update(['status' => GameStatus::LOST]);

		$this->message = 'You lost!';

		return $this;
	}

	private function done(): array
	{
		$response = ['tileImage' => asset($this->nextPrize->image_src)];
        if (filled($this->message)) {
            $response['message'] = $this->message;
        }

		return $response;
	}
}
