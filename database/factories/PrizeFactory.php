<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Segment;
use App\Models\Campaign;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Prize>
 */
final class PrizeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'campaign_id' => Campaign::factory(),
            'name' => fake()->word,
            'description' => fake()->sentence,
            'segment' => Segment::LOW,
            'weight' => 25,
            'starts_at' => now(),
            'ends_at' => now()->addDays(5),
        ];
    }
}
