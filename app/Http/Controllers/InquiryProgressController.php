<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inquiry;
use App\Models\InquiryProgress;
use Illuminate\Support\Facades\Auth;

class InquiryProgressController extends Controller
{
    public function store(Request $request, $inquiryId)
    {
        $request->validate([
            'status'  => 'required|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        // Create progress log
        $progress = InquiryProgress::create([
            'inquiry_id' => $inquiryId,
            'status'     => $request->status,
            'remarks'    => $request->remarks,
            'updated_by' => Auth::id(),
        ]);

        // 🔄 Update inquiry main table for real-time display to public
        $inquiry = Inquiry::find($inquiryId);
        $inquiry->investigation_status = $request->status;
        $inquiry->save();

        return redirect()->back()->with('success', 'Inquiry status updated!');
    }

    public function show($id)
    {
        $inquiry = Inquiry::with('assignment.agency')->findOrFail($id);
        $progress = InquiryProgress::where('inquiry_id', $id)->orderBy('created_at', 'desc')->get();

        return view('inquiry.progress', compact('inquiry', 'progress'));
    }

}
