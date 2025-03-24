<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\View\View;

class FrontendController extends Controller
{
    /**
     * @throws \JsonException
     */
    public function loadCampaign(Campaign $campaign): View
    {
        $config = [
            'apiPath' => '/api/flip',
            'gameId' => 1,
        ];

        return view('frontend.index', ['config' => $config]);
    }

    public function placeholder(): View
    {
        return view('frontend.placeholder');
    }
}
