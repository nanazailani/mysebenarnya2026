<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inquiry;
use App\Models\InquiryProgress;
use Illuminate\Support\Facades\Auth;
use App\Notifications\InquiryStatusUpdatedNotification; // ADD import

class InquiryProgressController extends Controller
{
    public function store(Request $request, $inquiryId)
    {
        $request->validate([
            'investigation_status' => 'required|string',
            'final_status'         => 'required|in:Pending,Approved,Fake,Rejected',
        ]);

        // Create progress
        $progress = InquiryProgress::create([
            'inquiry_id' => $inquiryId,
            'status'     => $request->final_status,
            'remarks'    => $request->investigation_status,
            'updated_by' => Auth::id(),
        ]);

        // Update inquiry main table for real-time display to public
        $inquiry = Inquiry::find($inquiryId);
        $inquiry->investigation_status = $request->investigation_status;
        $inquiry->status = $request->final_status;
        $inquiry->save();

        // ADDED: Notify the public user who submitted this inquiry
        if ($inquiry->user) {
            $inquiry->user->notify(
                new InquiryStatusUpdatedNotification($inquiry)
            );
        }

        return redirect()->back()->with('success', 'Inquiry status updated!');
    }

    public function show($id)
    {
        $inquiry = Inquiry::with('assignment.agency')->findOrFail($id);

        $progress = InquiryProgress::where('inquiry_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('inquiry.progress', compact('inquiry', 'progress'));
    }
}



