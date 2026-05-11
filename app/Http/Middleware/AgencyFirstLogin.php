<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgencyFirstLogin
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user->role === 'agency_staff' && $user->is_first_login) {
            return $next($request);
        }

        // If not agency first‐time, kick them back
        return redirect()->route('dashboard.redirect');
    }
}
