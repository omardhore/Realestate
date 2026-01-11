<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function edit(Request $request): View
    {
        // Show the profile edit form with the current user data
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     *
     * @param  \App\Http\Requests\ProfileUpdateRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Fill the user model with validated data
        $request->user()->fill($request->validated());

        // If email was changed, reset verification
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // Save the updated user data
        $request->user()->save();

        // Redirect back to the profile edit page with a status message
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account after validating password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Validate the user's password before deletion
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Log out the user
        Auth::logout();

        // Delete the user account
        $user->delete();

        // Invalidate and regenerate session token for security
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to the home page
        return Redirect::to('/');
    }
}
