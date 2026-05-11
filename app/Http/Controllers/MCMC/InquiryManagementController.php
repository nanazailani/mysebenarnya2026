<?php

namespace App\Http\Controllers\MCMC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inquiry;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;

class InquiryManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Inquiry::query();

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('sort') && $request->sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $inquiries = $query->get();

        return view('mcmc.inquiries.index', compact('inquiries'));
    }

    public function show($id)
    {
        $inquiry = Inquiry::findOrFail($id);
        return view('mcmc.inquiries.show', compact('inquiry'));
    }

    public function update(Request $request, $id)
    {
        $inquiry = Inquiry::findOrFail($id);
        $inquiry->status = $request->input('status');
        $inquiry->save();

        return redirect()->route('mcmc.inquiries')->with('success', 'Inquiry status updated.');
    }

    public function downloadPdf(Request $request)
    {
        $query = Inquiry::query();

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('sort') && $request->sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $inquiries = $query->get();

        $pdf = Pdf::loadView('mcmc.inquiries.pdf', compact('inquiries'));
        return $pdf->download('inquiries_report.pdf');

    }
}
