<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Game;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class ApiControllerTest extends TestCase
{
    #[DataProvider('invalidParamsProvider')]
    public function test_flip_fails_validation_when_params_are_missing_or_invalid(array $params, string $missingKey)
    {
        $this->withoutExceptionHandling();

        $this->expectException(ValidationException::class);

        $this
            ->postJson('/api/flip', $params)
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonValidationErrors([$missingKey]);
    }

    public static function invalidParamsProvider(): array
    {
        return [
            'Missing gameId' => [['tileIndex' => 5], 'gameId'],
            'Missing tileIndex' => [['gameId' => 1], 'tileIndex'],
            'Non-existent gameId' => [['gameId' => 99999, 'tileIndex' => 5], 'gameId'],
            'gameId is not integer' => [['gameId' => 'invalid-game-id', 'tileIndex' => 5], 'gameId'],
            'tileIndex is not int' => [['gameId' => 1, 'tileIndex' => 'invalid-tile-index'], 'tileIndex'],
            'tileIndex negative' => [['gameId' => 1, 'tileIndex' => -1], 'tileIndex'],
        ];
    }

    public function test_flip_success()
    {
        $game = Game::factory()->create();
        $this->postJson('/api/flip', ['gameId' => $game->id, 'tileIndex' => 1])
            ->assertSuccessful();
    }
}
