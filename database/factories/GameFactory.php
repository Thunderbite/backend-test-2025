<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\Prize;
use Illuminate\Database\Eloquent\Factories\Factory;

final class GameFactory extends Factory
{
    public function definition(): array
    {
        $campaign = Campaign::query()->inRandomOrder()->firstOr(fn () => Campaign::factory()->create());

        return [
            'campaign_id' => $campaign->id,
            'won_prize_id' => Prize::where('campaign_id', $campaign->id)->inRandomOrder()->first()?->id,
            'account' => $this->faker->userName(),
            'revealed_at' => now()->subDays(random_int(1, 10)),
        ];
    }
}
