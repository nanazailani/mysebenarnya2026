<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inquiry;
use App\Models\InquiryAssignment;
use Illuminate\Support\Facades\Auth;
use App\Notifications\InquiryRejectedNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\User;


class InquiryTrackingController extends Controller
{
    // 1. Display list of assigned inquiries
    public function index(Request $request)
    {
        $agencyId = Auth::id(); // logged-in agency staff ID

        // Get inquiry IDs assigned to this agency
        $assignedInquiryIds = InquiryAssignment::where('agency_id', $agencyId)
            ->pluck('inquiry_id');

        // Build the query
        $query = Inquiry::whereIn('id', $assignedInquiryIds);

        // Apply filters
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('subject')) {
            $query->where('subject', 'like', '%' . $request->subject . '%');
        }

        $inquiries = $query->latest()->get();

        return view('agency.inquiries.index', compact('inquiries'));
    }

    // 2. Show specific inquiry details (only if assigned to the agency)
    public function show($id)
    {
        $inquiry = Inquiry::with('assignment')->findOrFail($id);
        $agencyId = Auth::id();

        // Make sure the inquiry is assigned to this agency
        $isAssigned = InquiryAssignment::where('inquiry_id', $inquiry->id)
            ->where('agency_id', $agencyId)
            ->exists();

        if (! $isAssigned) {
            abort(403, 'Unauthorized to view this inquiry.');
        }

        return view('agency.inquiries.show', compact('inquiry'));
    }

    // 3. Handle update for investigation status
    public function update(Request $request, $id)
{
    $request->validate([
        'investigation_status' => 'required|string',
        'final_status' => 'required|in:Pending,Approved,Rejected',
    ]);

    $inquiry = Inquiry::findOrFail($id);

    // Ensure agency owns this
    if (!$inquiry->assignment || $inquiry->assignment->agency_id !== Auth::id()) {
        abort(403);
    }

    $inquiry->investigation_status = $request->investigation_status;
    $inquiry->status = $request->final_status;
    $inquiry->save();

    // 🔔 Notify MCMC if Rejected
    if ($request->final_status === 'Rejected') {
        $mcmcUser = User::where('email', $inquiry->assignment->mcmcStaff->email)->first();
        if ($mcmcUser) {
            Notification::send($mcmcUser, new InquiryRejectedNotification($inquiry));
        }
    }

    return redirect()->route('agency.inquiries')->with('success', 'Inquiry has been reviewed and updated.');
}


}
