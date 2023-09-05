<?php

namespace Database\Seeders;

use App\Models\Campaign;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Campaign::truncate();

        Campaign::create([
            'timezone' => 'Europe/London',
            'name' => 'Test Campaign 1',
            'slug' => 'test-campaign-1',
            'starts_at' => now()->startOfDay(),
            'ends_at' => now()->addDays(7)->endOfDay(),
        ]);

        Campaign::create([
            'timezone' => 'Europe/London',
            'name' => 'Test Campaign 2',
            'slug' => 'test-campaign-2',
            'starts_at' => now()->startOfDay(),
            'ends_at' => now()->addDays(7)->endOfDay(),
        ]);
    }
}
