<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function showProfile()
    {
        return view('profile.index', [
            'user' => auth()->user(),
        ]);
    }

    public function edit()
    {
        return view('profile.edit', [
            'user' => auth()->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        // ──────────────────────────────────────────────────────
        // 1) VALIDATE ALL FIELDS INCLUDING AVATAR
        // ──────────────────────────────────────────────────────
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'contact'      => 'nullable|string|max:50',
            'agency_name'  => 'nullable|string|max:255',
            'avatar'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'password'     => 'nullable|string|min:8|confirmed',
        ]);

        // ──────────────────────────────────────────────────────
        // 2) UPDATE SIMPLE FIELDS
        // ──────────────────────────────────────────────────────
        $user->name        = $request->input('name');
        $user->email       = $request->input('email');
        $user->contact     = $request->input('contact');
        $user->agency_name = $request->input('agency_name');

        // ──────────────────────────────────────────────────────
        // 3) HANDLE AVATAR UPLOAD (IF PROVIDED)
        // ──────────────────────────────────────────────────────
        if ($request->hasFile('avatar')) {
            // 3a) Delete the old file (if there is one)
            if ($user->avatar) {
                // This deletes from storage/app/public/avatars/…
                Storage::disk('public')->delete($user->avatar);
            }

            // 3b) Store the new file under storage/app/public/avatars/
            $file     = $request->file('avatar');
            $filename = uniqid('avatar_') . '.' . $file->getClientOriginalExtension();
            $path     = $file->storeAs('avatars', $filename, 'public');
            // $path is something like "avatars/avatar_abcdef123.jpg"

            // 3c) Save that path into the DB column `avatar`
            $user->avatar = $path;
        }

        // ──────────────────────────────────────────────────────
        // 4) HANDLE PASSWORD CHANGE (IF PROVIDED)
        // ──────────────────────────────────────────────────────
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        // ──────────────────────────────────────────────────────
        // 5) SAVE EVERYTHING
        // ──────────────────────────────────────────────────────
        $user->save();

        // ──────────────────────────────────────────────────────
        // 6) REDIRECT BACK WITH A SUCCESS MESSAGE
        // ──────────────────────────────────────────────────────
        if ($user->role === 'agency_staff') {
            return redirect()
                ->route('agency.profile')
                ->with('status', 'Profile updated successfully.');
        } elseif ($user->role === 'mcmc_staff') {
            return redirect()
                ->route('mcmc.profile')
                ->with('status', 'Profile updated successfully.');
        } else {
            return redirect()
                ->route('profile')
                ->with('status', 'Profile updated successfully.');
        }
    }
}
