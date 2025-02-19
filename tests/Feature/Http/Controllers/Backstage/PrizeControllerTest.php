<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Backstage;

use App\Models\Campaign;
use App\Models\Prize;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class PrizeControllerTest extends TestCase
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
            ->get(route('backstage.prizes.index'))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('backstage.prizes.index');
    }

    public function test_create()
    {
        $this->withUser()
            ->get(route('backstage.prizes.create'))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('backstage.prizes.create')
            ->assertViewHas('prize');
    }

    public function test_store()
    {
        Storage::fake('public');

        $formData = [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'weight' => 20,
            'starts_at' => '01-03-2025 12:00:00',
            'ends_at' => '01-04-2025 12:00:00',
            'segment' => 'low',
            'daily_volume' => 2,
            'image_src' => UploadedFile::fake()->image('prize.jpg')->size(400),
        ];

        $this->withUser()
            ->post(route('backstage.prizes.store'), $formData)
            ->assertRedirect(route('backstage.prizes.index'))
            ->assertSessionHas('success', 'The prize has been created!');

        $this->assertDatabaseHas('prizes', [
            'name' => $formData['name'],
            'weight' => $formData['weight'],
            'segment' => $formData['segment'],
            'campaign_id' => $this->campaign->id,
        ]);
    }

    public function test_edit()
    {
        $prize = Prize::factory()->create();

        $this->withUser()
            ->get(route('backstage.prizes.edit', $prize))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('backstage.prizes.edit')
            ->assertViewHas('prize');
    }

    public function test_update()
    {
        Storage::fake('public');
        $prize = Prize::factory()->create();

        $formData = [
            'name' => '_Updated_',
            'description' => $this->faker->sentence,
            'weight' => 20,
            'starts_at' => '01-03-2026 12:00:00',
            'ends_at' => '01-04-2026 12:00:00',
            'segment' => 'high',
            'daily_volume' => 2,
            'image_src' => UploadedFile::fake()->image('prize.jpg')->size(400),
        ];

        $this->withUser()
            ->put(route('backstage.prizes.update', $prize), $formData)
            ->assertRedirect(route('backstage.prizes.edit', $prize))
            ->assertSessionHas('success', 'The prize has been updated!');

        $this->assertDatabaseHas('prizes', [
            'name' => $formData['name'],
            'weight' => $formData['weight'],
            'segment' => $formData['segment'],
            'campaign_id' => $this->campaign->id,
        ]);
    }

    public function test_destroy()
    {
        $prize = Prize::factory()->create();

        $this->withUser()
            ->delete(route('backstage.prizes.destroy', $prize))
            ->assertRedirect(route('backstage.prizes.index'))
            ->assertSessionHas('success', 'The prize has been deleted!');

        $this->assertDatabaseMissing('prizes', ['id' => $prize->id]);
    }
}
