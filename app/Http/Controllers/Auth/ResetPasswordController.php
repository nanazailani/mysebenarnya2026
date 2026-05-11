<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    /**
     * Show the password reset form (with token).
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Handle the password reset logic.
     */
    public function reset(Request $request)
    {
        // Validate request data
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // Attempt to reset the password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // Update the user’s password
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        // If reset successful, redirect to login with status; otherwise, error
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
