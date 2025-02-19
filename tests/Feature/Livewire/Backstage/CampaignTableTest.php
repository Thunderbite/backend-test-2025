<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Backstage;

use App\Livewire\Backstage\CampaignTable;
use App\Models\Campaign;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

final class CampaignTableTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_it_renders_correctly()
    {
        Livewire::actingAs($this->user)
            ->test(CampaignTable::class)
            ->assertStatus(200)
            ->assertViewHas('columns')
            ->assertViewHas('resource', 'campaigns');
    }

    public function test_it_displays_campaigns()
    {
        $campaigns = Campaign::factory()->count(3)->create();

        Livewire::actingAs($this->user)
            ->test(CampaignTable::class)
            ->assertSee($campaigns->first()->name)
            ->assertSee($campaigns->first()->timezone)
            ->assertSee($campaigns->first()->starts_at->format('Y-m-d H:i:s'))
            ->assertSee($campaigns->first()->ends_at->format('Y-m-d H:i:s'));
    }

    public function test_it_can_sort_campaigns()
    {
        Campaign::factory()->create(['name' => 'Campaign A']);
        Campaign::factory()->create(['name' => 'Campaign B']);

        Livewire::actingAs($this->user)
            ->test(CampaignTable::class)
            ->call('sortBy', 'name')
            ->assertSeeInOrder(['Campaign A', 'Campaign B']);

        Livewire::actingAs($this->user)
            ->test(CampaignTable::class)
            ->call('sortBy', 'name')
            ->call('sortBy', 'name') // Reverse order
            ->assertSeeInOrder(['Campaign B', 'Campaign A']);
    }

    public function test_it_can_search_campaigns()
    {
        Campaign::factory()->create(['name' => 'Special Campaign']);
        Campaign::factory()->create(['name' => 'Regular Campaign']);

        Livewire::actingAs($this->user)
            ->test(CampaignTable::class)
            ->set('search', 'Special')
            ->assertSee('Special Campaign')
            ->assertDontSee('Regular Campaign');
    }
}
