<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\GameStatus;
use App\Enums\Segment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Game extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'revealed_at' => 'datetime',
            'won_at' => 'datetime',
            'status' => GameStatus::class,
            'segment' => Segment::class,
        ];
    }

    public static function filter(?string $account = null, ?int $prizeId = null, ?string $startDate = null, ?string $endDate = null)
    {
        return self::query()
            ->when($account, fn (Builder $query) => $query->where('games.account', 'LIKE', "%{$account}%"))
            ->when($prizeId, fn (Builder $query) => $query->whereWonPrizeId($prizeId))
            ->when($startDate, function (Builder $query) use ($startDate) {
                $formattedDate = Carbon::createFromFormat('d-m-Y H:i:s', $startDate)->format('Y-m-d H:i:s');
                $query->where('revealed_at', '>=', $formattedDate);
            })
            ->when($endDate, function (Builder $query) use ($endDate) {
                $formattedDate = Carbon::createFromFormat('d-m-Y H:i:s', $endDate)->format('Y-m-d H:i:s');
                $query->where('revealed_at', '<=', $formattedDate);
            });
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function wonPrize(): BelongsTo
    {
        return $this->belongsTo(Prize::class);
    }

    public function moves(): HasMany
    {
        return $this->hasMany(GameMove::class);
    }

    public function winningPrizeId(): ?int
    {
        return $this->moves()
            ->selectRaw('prize_id')
            ->groupBy('prize_id')
            ->havingRaw('COUNT(id) >= ?', [config('game.minimum-matching-tiles-to-win')])
            ->value('prize_id');
    }

    public function hasExceededAllowedMovesBeforeLoss(): bool
    {
        if (! array_key_exists('moves_count', $this->attributes)) {
            $this->loadCount('moves');
        }

        return (int) $this->moves_count >= (int) config('game.maximum-game-moves-before-loss');
    }
}
