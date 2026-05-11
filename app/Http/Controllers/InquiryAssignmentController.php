<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InquiryAssignment;
use App\Models\Inquiry;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use PDF;

class InquiryAssignmentController extends Controller
{
    // Store a new assignment
    public function assignToAgency(Request $request)
    {
        $request->validate([
            'inquiry_id' => 'required|integer',
            'agency_id' => 'required|integer',
            'notes' => 'nullable|string',
        ]);

        $assignment = new InquiryAssignment();
        $assignment->inquiry_id = $request->inquiry_id;
        $assignment->agency_id = $request->agency_id;
        $assignment->mcmc_staff_id = Auth::id(); // assumes MCMC staff is logged in
        $assignment->notes = $request->notes;
        $assignment->assigned_at = now();
        $assignment->save();

        return redirect()->back()->with('success', 'Inquiry successfully assigned to agency.');
    }

    public function showForm()
    {
        $agencies = \App\Models\User::where('role', 'agency_staff')->get();
        $inquiries = Inquiry::whereDoesntHave('assignment')->get();

        return view('mcmc.assign', compact('agencies', 'inquiries'));
    }

    public function showReport(Request $request)
{
    // Get filters
    $month = $request->input('month');
    $year = $request->input('year');
    $agencyId = $request->input('agency_id');

    // Query: join assignments with agency user
    $query = DB::table('inquiry_assignments')
        ->join('users', 'inquiry_assignments.agency_id', '=', 'users.id')
        ->select(
            'users.agency_name',
            DB::raw('COUNT(inquiry_assignments.id) as total_inquiries'),
            DB::raw('MONTH(inquiry_assignments.assigned_at) as month'),
            DB::raw('YEAR(inquiry_assignments.assigned_at) as year')
        )
        ->groupBy('users.agency_name', 'month', 'year');

    // Apply filters
    if ($month) $query->whereMonth('assigned_at', $month);
    if ($year) $query->whereYear('assigned_at', $year);
    if ($agencyId) $query->where('agency_id', $agencyId);

    $reportData = $query->get();
    $agencies = User::where('role', 'agency_staff')->get();

    return view('mcmc.assignment-report', compact('reportData', 'agencies', 'month', 'year', 'agencyId'));
}

public function exportPdf()
{
    $month = request('month');
    $year = request('year');
    $agencyId = request('agency');

    $query = DB::table('inquiry_assignments')
        ->join('users', 'inquiry_assignments.agency_id', '=', 'users.id')
        ->select(
            'users.agency_name',
            DB::raw('MONTH(inquiry_assignments.assigned_at) as month'),
            DB::raw('YEAR(inquiry_assignments.assigned_at) as year'),
            DB::raw('COUNT(*) as total_inquiries')
        )
        ->groupBy('users.agency_name', 'month', 'year');

    if ($month) $query->whereMonth('inquiry_assignments.assigned_at', $month);
    if ($year) $query->whereYear('inquiry_assignments.assigned_at', $year);
    if ($agencyId) $query->where('inquiry_assignments.agency_id', $agencyId);

    $reportData = $query->get();

    $pdf = PDF::loadView('mcmc.assignment-report-pdf', compact('reportData'));

    return $pdf->download('assignment-report.pdf');
}

public function update(Request $request)
{
    $request->validate([
        'inquiry_id' => 'required|exists:inquiries,id',
        'agency_id' => 'required|exists:users,id',
    ]);

    // Remove old assignment if exists
    \App\Models\InquiryAssignment::where('inquiry_id', $request->inquiry_id)->delete();

    // Reassign to new agency
    \App\Models\InquiryAssignment::create([
        'inquiry_id' => $request->inquiry_id,
        'agency_id' => $request->agency_id,
        'assigned_by' => auth()->id(),
        'assigned_at' => now(),
    ]);

    // Reset inquiry status
    $inquiry = \App\Models\Inquiry::find($request->inquiry_id);
    $inquiry->status = 'Pending';
    $inquiry->investigation_status = null;
    $inquiry->save();

    return redirect()->route('mcmc.inquiries')->with('success', 'Inquiry reassigned successfully.');
}



}
