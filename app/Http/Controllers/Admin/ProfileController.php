<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the profile settings page.
     *
     * @return \Illuminate\Http\Response
     */
    public function settings()
    {
        return view('admin.profile.settings');
    }

    /**
     * Update the user's email address.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
            'email_confirmation' => ['required', 'string', 'email', 'same:email'],
            'current_password_email' => ['required', 'current_password'],
        ], [
            'email.unique' => 'The email address is already in use.',
            'email_confirmation.same' => 'The email confirmation does not match.',
            'current_password_email.current_password' => 'The provided password is incorrect.',
        ]);

        Auth::user()->update([
            'email' => $request->email,
        ]);

        return redirect()->back()->with('success', 'Your email address has been updated successfully.');
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required', 
                'confirmed', 
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ], [
            'current_password.current_password' => 'The provided password is incorrect.',
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Your password has been updated successfully.');
    }

    /**
     * Update the user's profile photo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        // Delete old photo if exists
        if (Auth::user()->photo) {
            Storage::disk('public')->delete(Auth::user()->photo);
        }

        // Store new photo
        $path = $request->file('photo')->store('profile-photos', 'public');

        Auth::user()->update([
            'photo' => $path,
        ]);

        return redirect()->back()->with('success', 'Your profile photo has been updated successfully.');
    }

    /**
     * Remove the user's profile photo.
     *
     * @return \Illuminate\Http\Response
     */
    public function removePhoto()
    {
        // Delete photo if exists
        if (Auth::user()->profile_photo_path) {
            Storage::disk('public')->delete(Auth::user()->profile_photo_path);
        }

        Auth::user()->update([
            'profile_photo_path' => null,
        ]);

        return redirect()->back()->with('success', 'Your profile photo has been removed.');
    }
}