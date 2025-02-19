<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Backstage;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class UserControllerTest extends TestCase
{
    use WithFaker;

    public Campaign $campaign;

    public function withUser()
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = User::factory()->create();
        $this->campaign = Campaign::factory()->create();

        return $this->actingAs($user)->withSession(['activeCampaign' => $this->campaign->id]);
    }

    public function test_index()
    {
        $this->withUser()
            ->get(route('backstage.users.index'))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('backstage.users.index');
    }

    public function test_create()
    {
        $this->withUser()
            ->get(route('backstage.users.create'))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('backstage.users.create')
            ->assertViewHas('user');
    }

    public function test_store()
    {
        $formData = [
            'name' => $this->faker->word,
            'email' => $this->faker->email,
            'level' => 'admin',
        ];

        $this->withUser()
            ->post(route('backstage.users.store'), $formData)
            ->assertRedirect(route('backstage.users.index'))
            ->assertSessionHas('success', 'The user has been created!');

        $this->assertDatabaseHas('users', [
            'name' => $formData['name'],
            'email' => $formData['email'],
            'level' => $formData['level'],
        ]);
    }

    public function test_edit()
    {
        $user = user::factory()->create();

        $this->withUser()
            ->get(route('backstage.users.edit', $user))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('backstage.users.edit')
            ->assertViewHas('user');
    }

    public function test_update()
    {
        $user = user::factory()->create();

        $formData = [
            'name' => $this->faker->word,
            'email' => $this->faker->email,
            'level' => 'admin',
        ];

        $this->withUser()
            ->put(route('backstage.users.update', $user), $formData)
            ->assertRedirect(route('backstage.users.edit', $user))
            ->assertSessionHas('success', 'The user details have been saved!');

        $this->assertDatabaseHas('users', [
            'name' => $formData['name'],
            'email' => $formData['email'],
            'level' => $formData['level'],
        ]);
    }

    public function test_destroy()
    {
        $user = user::factory()->create();

        $this->withUser()
            ->delete(route('backstage.users.destroy', $user))
            ->assertRedirect(route('backstage.users.index'))
            ->assertSessionHas('success', 'The user has been removed!');

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
