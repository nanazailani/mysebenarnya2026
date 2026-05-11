<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request.
     */
    public function register(Request $request)
    {
        // 1) Validate the incoming request
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // 2) Generate a 6‐character verification code
        $verification_code = Str::random(6);

        // 3) Determine role based on email domain
        $email = $request->email;
        if (Str::endsWith($email, '@mcmc.com')) {
            $role = 'mcmc_staff';
            // Auto‐verify MCMC staff emails; remove this line if you want them to use the code flow
            $emailVerifiedAt = now();
        } elseif (Str::endsWith($email, '@agencyname.gov.my')) {
            $role = 'agency_staff';
            $emailVerifiedAt = null; // they still need to verify via code
        } else {
            $role = 'public_user';
            $emailVerifiedAt = null; // or auto‐verify if you prefer
        }

        // 4) Create the new user
        $user = User::create([
            'name'              => $request->name,
            'email'             => $email,
            'password'          => Hash::make($request->password),
            'verification_code' => $verification_code,
            'role'              => $role,
            'is_first_login'    => ($role === 'agency_staff') ? true : false,
            'email_verified_at' => $emailVerifiedAt,
        ]);

        // 5) Send the verification code via email (only if we haven’t auto‐verified)
        if (is_null($emailVerifiedAt)) {
            Mail::raw("Your verification code is: {$verification_code}", function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Email Verification Code');
            });
        }

        // 6) Redirect them to the “enter code” notice page
        return redirect()->route('verification.notice');
    }

    /**
     * Show a “verification notice” (telling user to check email for code).
     */
    public function showVerificationNotice()
    {
        return view('auth.verify_notice');
    }

    /**
     * Show the form where the user inputs email + code.
     */
    public function showVerificationForm()
    {
        return view('auth.verify');
    }

    /**
     * Handle the verification‐code submission.
     */
    public function verifyCode(Request $request)
    {
        // 1) Validate the incoming request
        $request->validate([
            'email'             => 'required|email|exists:users,email',
            'verification_code' => 'required|string',
        ]);

        // 2) Look up that user by email + code
        $user = User::where('email', $request->email)
            ->where('verification_code', $request->verification_code)
            ->first();

        // 3) If no match, send back with an error
        if (! $user) {
            return back()->withErrors([
                'verification_code' => 'Invalid code provided.'
            ]);
        }

        // 4) Mark email as verified & clear the stored code
        $user->email_verified_at = now();
        $user->verification_code = null;
        $user->save();

        // 5) Redirect to login with a success message
        return redirect()
            ->route('login')
            ->with('status', 'Email verified! You can now log in.');
    }
}
