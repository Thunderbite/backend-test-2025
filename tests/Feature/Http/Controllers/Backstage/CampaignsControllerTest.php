<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Backstage;

use App\Models\Campaign;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class CampaignsControllerTest extends TestCase
{
    public function withUser()
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = User::factory()->create();

        return $this->actingAs($user);
    }

    public function test_index()
    {
        $this->withUser()
            ->get('/backstage/campaigns')
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('backstage.campaigns.index');
    }

    public function test_create()
    {
        $this->withUser()
            ->get('/backstage/campaigns/create')
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('backstage.campaigns.create')
            ->assertViewHas('campaign');
    }

    public function test_store()
    {
        $formData = [
            'name' => 'Black Friday Sale',
            'timezone' => 'America/New_York',
            'starts_at' => now()->toDateTimeString(),
            'ends_at' => now()->addDays(7)->toDateTimeString(),
        ];

        $this->withUser()
            ->post(route('backstage.campaigns.store'), $formData)
            ->assertRedirect(route('backstage.campaigns.index'))
            ->assertSessionHas('success', 'The campaign has been created!');

        $this->assertDatabaseHas('campaigns', $formData);
    }

    public function test_edit()
    {
        $campaign = Campaign::factory()->create();

        $this->withUser()
            ->get(route('backstage.campaigns.edit', $campaign))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('backstage.campaigns.edit')
            ->assertViewHas('campaign');
    }

    public function test_update()
    {
        $campaign = Campaign::factory()->create();

        $formData = [
            'name' => '_Updated_',
            'timezone' => 'UTC',
            'starts_at' => now()->toDateTimeString(),
            'ends_at' => now()->addDays(7)->toDateTimeString(),
        ];

        $this->withUser()
            ->put(route('backstage.campaigns.update', $campaign), $formData)
            ->assertRedirect(route('backstage.campaigns.edit', $campaign))
            ->assertSessionHas('success', 'The campaign details have been updated!');

        $this->assertDatabaseHas('campaigns', ['name' => '_Updated_', 'id' => $campaign->id]);
    }

    public function test_destroy()
    {
        $campaign = Campaign::factory()->create();

        $this->withUser()
            ->delete(route('backstage.campaigns.destroy', $campaign))
            ->assertRedirect(route('backstage.campaigns.index'))
            ->assertSessionHas('success', 'The campaign has been deleted!');

        $this->assertDatabaseMissing('campaigns', ['id' => $campaign->id]);
    }

    public function test_use()
    {
        $campaign = Campaign::factory()->create();

        $this->withUser()
            ->get(route('backstage.campaigns.use', $campaign))
            ->assertRedirect(route('backstage.campaigns.index'))
            ->assertSessionHas('activeCampaign', $campaign->id);
    }
}
