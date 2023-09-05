<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Campaign;

class FrontendController extends Controller
{
    /**
     * @throws \JsonException
     */
    public function loadCampaign(Campaign $campaign): View
    {
        $jsonConfig = '{}';

        return view('frontend.index', ['config' => $jsonConfig]);
    }

    public function placeholder(): View
    {
        return view('frontend.placeholder');
    }
}
