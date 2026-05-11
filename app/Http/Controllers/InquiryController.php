<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Models\Inquiry;
use App\Models\User;
use App\Notifications\InquiryStatusUpdatedNotification;

class InquiryController extends Controller
{
    // Show inquiry submission form (Public User)
    public function create()
    {
        return view('inquiry.create');
    }

    // Handle submission (Public User)
    public function store(Request $request)
    {
        $request->validate([
            'full_name'     => 'required|string|max:255',
            'phone_number'  => 'required|string|max:20',
            'email'         => 'required|email|max:255',
            'subject'       => 'required|string|max:255',
            'message'       => 'required|string',
            'source_type'   => 'required|string|max:255',
            'source_url'    => 'nullable|url',
            'attachment'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $attachmentPath = $request->file('attachment')
            ? $request->file('attachment')->store('inquiries', 'public')
            : null;

        Inquiry::create([
            'user_id'       => Auth::id(),
            'full_name'     => $request->full_name,
            'phone_number'  => $request->phone_number,
            'email'         => $request->email,
            'subject'       => $request->subject,
            'message'       => $request->message,
            'source_type'   => $request->source_type,
            'source_url'    => $request->source_url,
            'attachment'    => $attachmentPath,
        ]);

        return redirect()->route('inquiry.thank-you');
    }

    // Show inquiry history (Public User)
    public function history()
    {
        $inquiries = Inquiry::with(['assignment.agency'])
            ->where('user_id', Auth::id())
            ->get();

        return view('inquiry.history', compact('inquiries'));
    }

    // Submit review (Agency)
    public function submitFinalReview(Request $request, $id)
    {
        $request->validate([
            'investigation_status'  => 'required|string',
            'final_status'          => 'required|string',
            'supporting_document'   => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        $inquiry = Inquiry::findOrFail($id);

        // Update review fields
        $inquiry->jurisdiction_notes = $request->investigation_status;
        $inquiry->status = $request->final_status;
        $inquiry->reviewed_by = Auth::user()->name; // Auto-filled reviewer

        // Upload document
        if ($request->hasFile('supporting_document')) {
            $inquiry->supporting_document = $request->file('supporting_document')
                ->store('supporting_documents', 'public');
        }

        $inquiry->status_updated_by = Auth::user()->user_Name;
        $inquiry->status_updated_at = now();
        $inquiry->save();

        // Notify public user
        if ($inquiry->user) {
            $inquiry->user->notify(new InquiryStatusUpdatedNotification($inquiry));
        }

        // Notify all MCMC staff
        $mcmcStaff = User::where('role', 'mcmc_staff')->get();
        Notification::send($mcmcStaff, new InquiryStatusUpdatedNotification($inquiry));

        return redirect()->route('agency.inquiries')->with('success', 'Review submitted and status updated!');
    }
}
