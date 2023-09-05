<?php

namespace App\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backstage\Campaigns\UpdateRequest;
use App\Models\Campaign;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CampaignsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(): View
    {
        return view('backstage.campaigns.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(): View
    {
        return view('backstage.campaigns.create', [
            'campaign' => new Campaign(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function store(): RedirectResponse
    {
        // Validation
        $data = $this->validate(request(), [
            'name' => 'required|unique:campaigns|max:255',
            'timezone' => 'required',
            'starts_at' => 'required|date_format:d-m-Y H:i:s',
            'ends_at' => 'required|date_format:d-m-Y H:i:s',
        ]);

        //parse dates from campaign's timezone
        $startDate = Carbon::createFromFormat('d-m-Y H:i:s', $data['starts_at'], $data['timezone'])
            ->setTimezone('UTC');
        $data['starts_at'] = $startDate;

        $startDate = Carbon::createFromFormat('d-m-Y H:i:s', $data['ends_at'], $data['timezone'])
            ->setTimezone('UTC');
        $data['ends_at'] = $startDate;

        // Create the campaign
        $campaign = Campaign::create($data);

        // Set message
        session()->flash('success', 'The campaign has been created!');

        // Redirect
        return redirect()->route('backstage.campaigns.index');
    }

    /**
     * Display the specified resource.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Campaign $campaign): View
    {
        return view('backstage.campaigns.edit', compact('campaign'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     */
    public function update(Campaign $campaign): RedirectResponse
    {
        // Validation
        $data = $this->validate(request(), [
            'name' => 'required|max:255|unique:campaigns,name,'.$campaign->id,
            'timezone' => 'required',
            'starts_at' => 'required|date_format:d-m-Y H:i:s',
            'ends_at' => 'required|date_format:d-m-Y H:i:s',
        ]);

        //parse dates from campaign's timezone
        $startDate = Carbon::createFromFormat('d-m-Y H:i:s', $data['starts_at'], $data['timezone'])->setTimezone('UTC');
        $data['starts_at'] = $startDate;

        $startDate = Carbon::createFromFormat('d-m-Y H:i:s', $data['ends_at'], $data['timezone'])->setTimezone('UTC');
        $data['ends_at'] = $startDate;

        // Update the campaigns data
        $campaign->update($data);

        // Redirect
        session()->flash('success', 'The campaign details have been saved!');

        return redirect()->route('backstage.campaigns.edit', $campaign->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Campaign $campaign)
    {
        $campaign->forceDelete();

        if (request()->ajax()) {
            return response()->json(['status' => 'success']);
        }

        session()->flash('success', 'The campaign has been removed!');

        return redirect(route('backstage.campaigns.index'));
    }

    public function use(Campaign $campaign): RedirectResponse
    {
        session()->put('activeCampaign', $campaign->id);

        return redirect()->route('backstage.campaigns.index');
    }
}
