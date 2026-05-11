<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AgencyDashboardController extends Controller
{
    public function index()
    {
        // Fetch any agency‐specific data here
        return view('agency.dashboard');
    }
}
