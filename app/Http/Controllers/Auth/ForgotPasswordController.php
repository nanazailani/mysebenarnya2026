<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /**
     * Show the form to request a password reset link.
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle sending the password reset email.
     */
    public function sendResetLinkEmail(Request $request)
    {
        // Validate that the email exists in users table
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Attempt to send the reset link to this email
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // If successful, redirect back with a success status; otherwise show error
        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }
}
