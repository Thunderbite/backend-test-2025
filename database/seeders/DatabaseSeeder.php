<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Do basic seeding
        $this->call([
            UserSeeder::class,
            CampaignSeeder::class,
            PrizeSeeder::class,
            GameSeeder::class,
        ]);
    }
}
