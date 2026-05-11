<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AssignmentReportExport implements FromCollection, WithHeadings
{
    protected $reportData;

    public function __construct(Collection $reportData)
    {
        $this->reportData = $reportData;
    }

    public function collection()
    {
        return $this->reportData;
    }

    public function headings(): array
    {
        return ['Agency Name', 'Month', 'Year', 'Total Inquiries'];
    }
}