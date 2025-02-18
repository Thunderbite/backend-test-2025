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
		$this
			->setGame($gameId)
			->setNextPrize()
			->when($this->game->moves_count === 0, fn () => $this->markGameAsRevealed())
			->createMove($index);

			if ($winningPrizeId = $this->game->winningPrizeId()) {
				return $this->markAsWon($winningPrizeId)->done();
			}

			if ($this->game->hasExceededAllowedMovesBeforeLoss()) {
				$this->markAsLost();
			}

			return $this->done();
	}

	private function setGame(int $gameId): self
	{
		$this->game = Game::withCount('moves')->whereId($gameId)->firstOrFail();

		return $this;
	}

	private function setNextPrize(): self
	{
		$this->nextPrize = $this->game
		 	->campaign
			->prizes()
			->whereSegment($this->game->segment)
			->where('starts_at', '<=', now())
			->where('ends_at', '>=', now())
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
		$this->game->loadCount('moves');

		return $this;
	}

	private function markAsWon(int $winningPrizeId): self
	{
		$this->game->update(['won_prize_id' => $winningPrizeId, 'status' => GameStatus::WON]);

		$this->message = $this->nextPrize->description ?? 'You won a prize!';

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
		$response = ['tileImage' => asset($this->nextPrize->image_url)];
        if (filled($this->message)) {
            $response['message'] = $this->message;
        }

		return $response;
	}
}
