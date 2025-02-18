<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class FrontendControllerTest extends TestCase
{
    private function getWithQuery(string $slug, array $params)
    {
        $query = http_build_query($params);

        return $this->get(sprintf('/%s?%s', $slug, $query));
    }

    public function test_placeholder()
    {
        $this->get('/')->assertStatus(Response::HTTP_OK);
    }

    public function test_load_campaign_returns_404_with_invalid_campaign()
    {
        Campaign::whereSlug('invalid-slug-deleting-to-be-sure-it-does-not-exist')->delete();

        $this
            ->getWithQuery('/invalid-slug-deleting-to-be-sure-it-does-not-exist', ['a' => 'invalid', 'segment' => 'invalid'])
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_load_campaign_redirects_to_home_page_with_empty_a_or_segment_params()
    {
        $campaign = Campaign::factory()->create();

        $this
            ->getWithQuery($campaign->slug, ['a' => '', 'segment' => ''])
            ->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect('/');
    }

    #[DataProvider('missingLoadCampaignParamProvider')]
    public function test_load_campaign_fails_validation_when_params_are_missing(array $params, string $missingKey)
    {
        $this->withoutExceptionHandling();

        $campaign = Campaign::factory()->create();

        $this->expectException(ValidationException::class);

        $this
            ->getWithQuery($campaign->slug, $params)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([$missingKey]);
    }

    public static function missingLoadCampaignParamProvider(): array
    {
        return [
            'Missing a' => [['segment' => 'test'], 'a'],
            'Missing segment' => [['a' => 'valid'], 'segment'],
        ];
    }

    public function test_load_campaign_success()
    {
        $campaign = Campaign::factory()->create();

        $this->getWithQuery($campaign->slug, ['a' => 'account', 'segment' => 'high'])
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('frontend.index')
            ->assertViewHas('config');
    }
}
