<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Backstage;

use App\Models\Campaign;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class GameControllerTest extends TestCase
{
    public function withUser()
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = User::factory()->create();
        $campaign = Campaign::factory()->create();

        return $this->actingAs($user)->withSession(['activeCampaign' => $campaign->id]);
    }

    public function test_index()
    {
        $this->withUser()
            ->get(route('backstage.games.index'))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('backstage.games.index');
    }
}
