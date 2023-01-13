<?php

namespace App\Http\Controllers;

use App\Models\Campaign;

class FrontendController extends Controller
{
    /**
     * @throws \JsonException
     */
    public function loadCampaign(Campaign $campaign)
    {
        $jsonConfig = '{}';

        return view('frontend.index', ['config' => $jsonConfig]);
    }

    public function placeholder()
    {
        return view('frontend.placeholder');
    }
}
