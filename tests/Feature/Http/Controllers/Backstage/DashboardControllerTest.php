<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Backstage;

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class DashboardControllerTest extends TestCase
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
            ->get('/backstage')
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('backstage.dashboard.index');
    }
}
