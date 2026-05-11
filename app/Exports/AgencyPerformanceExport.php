<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AgencyPerformanceExport implements FromCollection, WithHeadings
{
    protected $summary;

    public function __construct($summary)
    {
        $this->summary = $summary;
    }

    public function collection()
    {
        return collect($this->summary)->map(function ($data, $agency) {
            return [
                'Agency'         => $agency,
                'Assigned'       => $data['assigned'],
                'Resolved'       => $data['resolved'],
                'Pending'        => $data['pending'],
                'Avg Resolution' => round($data['avg_days'], 1) . ' days',
            ];
        });
    }

    public function headings(): array
    {
        return ['Agency', 'Assigned', 'Resolved', 'Pending', 'Avg Resolution'];
    }
}
