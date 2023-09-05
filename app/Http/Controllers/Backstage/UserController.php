<?php

namespace App\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backstage\Users\StoreRequest;
use App\Http\Requests\Backstage\Users\UpdateRequest;
use App\Mail\Backstage\Users\WelcomeMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backstage.users.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('backstage.users.create', [
            'user' => new User(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request)
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
            //
    }

    /**
     * Show the form for editing the specified resource.
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('backstage.users.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRequest $request, User $user)
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        $user->forceDelete();

        if (request()->ajax()) {
            return response()->json(['status' => 'success']);
        }

        session()->flash('success', 'The user has been removed!');

        return redirect(route('backstage.users.index'));
    }
}
