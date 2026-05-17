<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Inquiry;

class DashboardRedirectController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'agency_staff') {
            // If it’s their very first login, send to password‐change page
            if ($user->is_first_login) {
                return redirect()->route('agency.first-login');
            }
            return redirect()->route('dashboard.agency');
        }

        if ($user->role === 'mcmc_staff') {
            return redirect()->route('dashboard.mcmc');
        }

        // Otherwise, public user
        return redirect()->route('dashboard.public');
    }

    public function publicDashboard()
    {
        $inquiries = Inquiry::with(['assignment' => function ($query) {
            $query->with('agency'); // Load the agency within the assignment
        }])
        ->where('user_id', Auth::id())
        ->get();

        return view('dashboard.public', compact('inquiries'));
    }

    public function history(Request $request)
    {
    $inquiries = Inquiry::with(['assignment.agency'])
        ->where('user_id', Auth::id())
        ->when($request->keyword, function ($query) use ($request) {
            $query->where('subject', 'like', '%' . $request->keyword . '%');
        })
        ->paginate(2);

    return view('inquiry.history', compact('inquiries'));
    }

}
