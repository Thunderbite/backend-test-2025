<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Prize;
use Illuminate\Database\Seeder;

class PrizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Prize::truncate();

        $campaigns = Campaign::all();

        foreach ($campaigns as $campaign) {
            Prize::insert([
                [
                    'campaign_id' => $campaign->id,
                    'name' => 'Low 1',
                    'level' => 'low',
                    'weight' => '25.00',
                    'starts_at' => now()->subDays(10)->startOfDay(),
                    'ends_at' => now()->addDays(7)->endOfDay(),
                ],
                [
                    'campaign_id' => $campaign->id,
                    'name' => 'Low 2',
                    'level' => 'low',
                    'weight' => '25.00',
                    'starts_at' => now()->subDays(10)->startOfDay(),
                    'ends_at' => now()->addDays(7)->endOfDay(),
                ],
                [
                    'campaign_id' => $campaign->id,
                    'name' => 'Low 3',
                    'level' => 'low',
                    'weight' => '50.00',
                    'starts_at' => now()->subDays(10)->startOfDay(),
                    'ends_at' => now()->addDays(7)->endOfDay(),
                ],
                [
                    'campaign_id' => $campaign->id,
                    'name' => 'Med 1',
                    'level' => 'med',
                    'weight' => '25.00',
                    'starts_at' => now()->subDays(10)->startOfDay(),
                    'ends_at' => now()->addDays(7)->endOfDay(),
                ],
                [
                    'campaign_id' => $campaign->id,
                    'name' => 'Med 2',
                    'level' => 'med',
                    'weight' => '25.00',
                    'starts_at' => now()->subDays(10)->startOfDay(),
                    'ends_at' => now()->addDays(7)->endOfDay(),
                ],
                [
                    'campaign_id' => $campaign->id,
                    'name' => 'Med 3',
                    'level' => 'med',
                    'weight' => '50.00',
                    'starts_at' => now()->subDays(10)->startOfDay(),
                    'ends_at' => now()->addDays(7)->endOfDay(),
                ],
                [
                    'campaign_id' => $campaign->id,
                    'name' => 'High 1',
                    'level' => 'high',
                    'weight' => '25.00',
                    'starts_at' => now()->subDays(10)->startOfDay(),
                    'ends_at' => now()->addDays(7)->endOfDay(),
                ],
                [
                    'campaign_id' => $campaign->id,
                    'name' => 'High 2',
                    'level' => 'high',
                    'weight' => '25.00',
                    'starts_at' => now()->subDays(10)->startOfDay(),
                    'ends_at' => now()->addDays(7)->endOfDay(),
                ],
                [
                    'campaign_id' => $campaign->id,
                    'name' => 'High 3',
                    'level' => 'high',
                    'weight' => '50.00',
                    'starts_at' => now()->subDays(10)->startOfDay(),
                    'ends_at' => now()->addDays(7)->endOfDay(),
                ],
            ]);
        }
    }
}
