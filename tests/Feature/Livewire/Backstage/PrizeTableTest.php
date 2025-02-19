<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Backstage;

use App\Livewire\Backstage\PrizeTable;
use App\Models\Prize;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

final class PrizeTableTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        session()->put('activeCampaign', 1);
    }

    public function test_it_renders_correctly()
    {
        Livewire::actingAs($this->user)
            ->test(PrizeTable::class)
            ->assertStatus(200)
            ->assertViewHas('columns')
            ->assertViewHas('resource', 'prizes');
    }

    public function test_displays_prizes()
    {
        $prizes = Prize::factory()->count(3)->create(['campaign_id' => session('activeCampaign')]);

        Livewire::actingAs($this->user)
            ->test(PrizeTable::class)
            ->assertSee($prizes->first()->name)
            ->assertSee($prizes->first()->segment)
            ->assertSee($prizes->first()->weight)
            ->assertSee($prizes->first()->starts_at->format('Y-m-d H:i:s'))
            ->assertSee($prizes->first()->ends_at->format('Y-m-d H:i:s'));
    }

    public function test_it_can_sort_prizes()
    {
        Prize::factory()->create(['name' => 'Prize A', 'campaign_id' => session('activeCampaign')]);
        Prize::factory()->create(['name' => 'Prize B', 'campaign_id' => session('activeCampaign')]);

        Livewire::actingAs($this->user)
            ->test(PrizeTable::class)
            ->call('sortBy', 'name')
            ->assertSeeInOrder(['Prize A', 'Prize B']);

        Livewire::actingAs($this->user)
            ->test(PrizeTable::class)
            ->call('sortBy', 'name')
            ->call('sortBy', 'name') // Reverse order
            ->assertSeeInOrder(['Prize B', 'Prize A']);
    }
}
