<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\Prize;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws Exception
     */
    public function definition(): array
    {
        $campaign = Campaign::inRandomOrder()->first();

        return [
            'campaign_id' => $campaign->id,
            'prize_id' => Prize::where('campaign_id', $campaign->id)->inRandomOrder()->first()->id,
            'account' => $this->faker->userName(),
            'revealed_at' => now()->subDays(random_int(1, 10)),
        ];
    }
}
