<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Game;
use Illuminate\View\View;

class FrontendController extends Controller
{
    /**
     * @throws \JsonException
     */
    public function loadCampaign(Campaign $campaign): View
    {
        $game = Game::firstOrCreate([
            'campaign_id' => $campaign->id,
            'account' => request('a'),
            'prize_id' => null
        ]);

        $jsonConfig = [
            'apiPath' => '/api/flip',
            'gameId' => $game->id,
            'revealedTiles' => [],
            'message' => null
        ];

        return view('frontend.index', ['config' => json_encode($jsonConfig)]);
    }

    public function placeholder(): View
    {
        return view('frontend.placeholder');
    }
}
