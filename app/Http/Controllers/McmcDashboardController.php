<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inquiry;

class McmcDashboardController extends Controller
{
    public function index()
    {
            $inquiries = \App\Models\Inquiry::with('assignment.agency')
            ->whereNotNull('status')
            ->orderBy('status_updated_at', 'desc')
            ->take(10)
            ->get();

        return view('mcmc.dashboard', compact('inquiries'));
    }
    public function processPage()
    {
        return view('mcmc.process.index'); // create this view
    }

}
