<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backstage\Prizes\StoreRequest;
use App\Http\Requests\Backstage\Prizes\UpdateRequest;
use App\Models\Prize;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\View\View;

final class PrizeController extends Controller
{
    /**
     * Display a listing of the prizes.
     */
    public function index(): View
    {
        return view('backstage.prizes.index');
    }

    /**
     * Show the form for creating a new prize.
     */
    public function create(): View
    {
        return view('backstage.prizes.create', [
            'prize' => new Prize,
        ]);
    }

    /**
     * Store a newly created prize in storage.
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        // Create the prize with validated data
        $data = Arr::except($request->validated(), 'image_src');
        $data['campaign_id'] = session('activeCampaign');

        $prize = Prize::create($data);
        $this->updateImageSrc($request, $prize);

        session()->flash('success', 'The prize has been created!');

        return redirect()->route('backstage.prizes.index');
    }

    /**
     * Show the form for editing the specified prize.
     */
    public function edit(Prize $prize): View
    {
        return view('backstage.prizes.edit', [
            'prize' => $prize,
        ]);
    }

    /**
     * Update the specified prize in storage.
     */
    public function update(UpdateRequest $request, Prize $prize): RedirectResponse
    {
        // Update the prize with validated data
        $data = Arr::except($request->validated(), 'image_src');
        $data['campaign_id'] = session('activeCampaign');

        $prize->update($data);
        $this->updateImageSrc($request, $prize);

        session()->flash('success', 'The prize has been updated!');

        return redirect()->route('backstage.prizes.edit', $prize->id);
    }

    private function updateImageSrc(StoreRequest|UpdateRequest $request, Prize $prize)
    {
        if ($request->hasFile('image_src')) {
            $file = $request->file('image_src');
            $path = $file->storeAs('prizes', sprintf('%d.%s', $prize->id, $file->getClientOriginalExtension()), 'public');
            $prize->update(['image_src' => $path]);
        }
    }

    /**
     * Remove the specified prize from storage.
     */
    public function destroy(Prize $prize): RedirectResponse
    {
        $prize->delete();

        session()->flash('success', 'The prize has been deleted!');

        return redirect()->route('backstage.prizes.index');
    }
}
