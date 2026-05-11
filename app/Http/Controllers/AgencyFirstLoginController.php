<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AgencyFirstLoginController extends Controller
{
    // Show the “change password” form
    public function showChangeForm()
    {
        return view('agency.first-login');
    }

    // Handle the change password submission
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user = Auth::user();

        // Update password & flip is_first_login to false
        $user->password = Hash::make($request->input('password'));
        $user->is_first_login = false;
        $user->save();

        // After changing, log them in and send to agency dashboard
        return redirect()->route('dashboard.agency')
            ->with('success', 'Password updated! Welcome to your dashboard.');
    }
}
