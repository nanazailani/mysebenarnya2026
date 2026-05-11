<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAgencyStaff
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Only allow if exactly role = 'agency_staff' AND is_first_login = false
        if ($user->role === 'agency_staff' && ! $user->is_first_login) {
            return $next($request);
        }

        // Otherwise, redirect back to the central redirect (or some other page)
        return redirect()->route('dashboard.redirect');
    }
}
