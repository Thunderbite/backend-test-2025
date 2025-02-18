<?php

namespace App\Http\Controllers;

use App\Actions\Frontend\LoadCampaignAction;
use App\Http\Requests\Frontend\LoadCampaignRequest;
use App\Models\Campaign;
use Illuminate\View\View;

class FrontendController extends Controller
{
    /**
     * @throws \JsonException
     */
    public function loadCampaign(LoadCampaignRequest $request, Campaign $campaign, LoadCampaignAction $action): View
    {
        return view('frontend.index', [
            'config' => $action->handle(
                $campaign,
                $request->input('a'),
                $request->input('segment')
            )
        ]);
    }

    public function placeholder(): View
    {
        return view('frontend.placeholder');
    }
}
