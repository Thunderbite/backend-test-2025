<?php

namespace App\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backstage\Users\StoreRequest;
use App\Http\Requests\Backstage\Users\UpdateRequest;
use App\Mail\Backstage\Users\WelcomeMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('backstage.users.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(): View
    {
        return view('backstage.users.create', [
            'user' => new User(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        // Setup the data
        $data = $request->validated();
        $password = Str::random(10);
        $data['password'] = bcrypt($password);

        // Create the user
        $user = User::create($data);

        // Setup a one time token
        $user->update([
            'ott' => encrypt($user->id),
        ]);

        // Send the welcome email
        Mail::to($user)->queue(new WelcomeMail($user));

        // Set message
        session()->flash('success', 'The user has been created!');

        // Redirect
        return redirect()->route('backstage.users.index');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
            //
    }

    /**
     * Show the form for editing the specified resource.
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user): View
    {
        return view('backstage.users.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param  int  $id
     */
    public function update(UpdateRequest $request, User $user): RedirectResponse
    {
        // Set base data
        $data = $request->validated();

        // Check if we have a new password
        if (isset($data['password'])) {
            if (auth()->user()->id !== $user->id) {
                unset($data['password']);
            } else {
                $data['password'] = bcrypt($data['password']);
            }
        }

        // Update the user
        $user->update($data);

        session()->flash('success', 'The user details have been saved!');

        return redirect()->route('backstage.users.edit', $user->id);
    }

    /**
     * Remove the specified resource from storage.
     * @param  int  $id
     */
    public function destroy(User $user): JsonResponse
    {
        $user->forceDelete();

        if (request()->ajax()) {
            return response()->json(['status' => 'success']);
        }

        session()->flash('success', 'The user has been removed!');

        return redirect(route('backstage.users.index'));
    }
}
