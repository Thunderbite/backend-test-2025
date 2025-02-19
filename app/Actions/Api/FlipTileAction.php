<?php

declare(strict_types=1);

namespace App\Actions\Api;

use App\Enums\GameStatus;
use App\Models\Game;
use App\Models\Prize;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Traits\Conditionable;

final class FlipTileAction
{
    use Conditionable;

    private Game $game;

    private ?Prize $nextPrize;

    public const MESSAGE_OUT_OF_PRIZES = 'All Prizes won! 🥹 Check back tomorrow! 🫵🏻';

    private ?string $message = null;

    public function handle(int $gameId, int $index): array
    {
        $this->setGame($gameId)->setNextPrize();
        if (blank($this->nextPrize)) {
            return $this->setOutOfPrizes()->done();
        }

        $this
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
        $this->nextPrize = $this
            ->game
            ->campaign
            ->prizes()
            ->whereSegment($this->game->segment)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->where(function (Builder $query) {
                $query
                    ->whereNull('prizes.daily_volume')
                    ->orWhereRaw('
                        prizes.daily_volume > (
                            SELECT
                                COUNT(*)
                            FROM games
                            WHERE games.won_prize_id = prizes.id
                            AND DATE(games.won_at) = CURDATE()
                        )'
                    );
            })
            ->orderByRaw('-LOG(1.0 - RAND()) / prizes.weight')
            ->first();

        return $this;
    }

    private function markGameAsRevealed(): self
    {
        $this->game->update(['revealed_at' => $this->game->campaign->now_in_time_zone]);

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
        $this->game->update([
            'won_prize_id' => $winningPrizeId,
            'won_at' => $this->game->campaign->now_in_time_zone,
            'status' => GameStatus::WON,
        ]);

        $this->message = $this->nextPrize->description ?? 'You won a prize!';

        return $this;
    }

    private function markAsLost(): self
    {
        $this->game->update(['status' => GameStatus::LOST]);

        $this->message = 'You lost!';

        return $this;
    }

    private function setOutOfPrizes(): self
    {
        $this->message = self::MESSAGE_OUT_OF_PRIZES;

        return $this;
    }

    private function done(): array
    {
        $tileImage = filled($this->nextPrize)
            ? asset($this->nextPrize->image_url)
            : asset('assets/empty.png');

        $response = ['tileImage' => $tileImage];
        if (filled($this->message)) {
            $response['message'] = $this->message;
        }

        return $response;
    }
}
