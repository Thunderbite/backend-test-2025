<?php

namespace App\Models;

use App\Enums\GameStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'revealed_at' => 'datetime',
            'status' => GameStatus::class
        ];
    }

    public static function filter(?string $account = null, ?int $prizeId = null, ?string $fromDate = null, ?string $tillDate = null)
    {
        $query = self::query();
        $campaign = Campaign::find(session('activeCampaign'));

        // When filtering by dates, keep in mind `revealed_at` should be stored in Campaign timezone

        return $query;
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

    public function hasExceededAllowedMovesBeforeLoss()
    {
        $this->loadCount('moves');
        return (int) $this->moves_count >= (int) config('game.maximum-game-moves-before-loss');
    }
}
