<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

final class CampaignFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->name();

        return [
            'timezone' => fake()->timezone(),
            'name' => $name,
            'slug' => Str::slug($name),
            'starts_at' => now(),
            'ends_at' => now()->addDays(10),
        ];
    }
}
