<?php

namespace App\Http\Controllers\MCMC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inquiry;
use Illuminate\Support\Carbon;

use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AgencyPerformanceExport;


class ProgressReportController extends Controller
{
    public function index()
    {
        $inquiries = Inquiry::with('assignment.agency')->get();

        // Group by agency name using a closure
        $summary = $inquiries->groupBy(function ($inquiry) {
            return optional(optional($inquiry->assignment)->agency)->agency_name ?? 'Unassigned';
        })->map(function ($group) {
            $resolved = $group->filter(fn($inq) => !is_null($inq->status_updated_at));

            return [
                'assigned' => $group->count(),
                'resolved' => $resolved->count(),
                'pending'  => $group->count() - $resolved->count(),
                'avg_days' => $resolved->avg(function ($inq) {
                    return Carbon::parse($inq->status_updated_at)->diffInDays($inq->created_at);
                }) ?? 0,
            ];
        });

        return view('mcmc.process.index', compact('summary'));
    }
    public function downloadPdf(Request $request)
    {
        $summary = $this->generateSummary($request);
        $pdf = Pdf::loadView('mcmc.process.pdf', compact('summary'));
        return $pdf->download('agency_performance_report.pdf');
    }

    public function downloadExcel(Request $request)
    {
        $summary = $this->generateSummary($request);
        return Excel::download(new AgencyPerformanceExport($summary), 'agency_performance_report.xlsx');
    }

    private function generateSummary(Request $request)
    {
        $query = Inquiry::with('assignment.agency');

        if ($request->from) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->to) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $inquiries = $query->get();

        if ($request->agency) {
            $inquiries = $inquiries->filter(function ($inquiry) use ($request) {
                return optional(optional($inquiry->assignment)->agency)->agency_name === $request->agency;
            });
        }

        return $inquiries->groupBy('assignment.agency.agency_name')->map(function ($group) {
            $resolved = $group->whereNotNull('status_updated_at');
            return [
                'assigned' => $group->count(),
                'resolved' => $resolved->count(),
                'pending'  => $group->count() - $resolved->count(),
                'avg_days' => $resolved->avg(function ($item) {
                    return \Carbon\Carbon::parse($item->status_updated_at)->diffInDays($item->created_at);
                }) ?? 0,
            ];
        });
    }


}
