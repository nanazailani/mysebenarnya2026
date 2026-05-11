@extends('layouts.dashboard')

@section('title', 'Inquiry Assignment Report')

@section('content')
<h2 class="text-xl font-bold mb-4">Inquiry Assignment Reporting</h2>

<form method="GET" action="{{ route('mcmc.assignment-report') }}" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
    {{-- Filter by Month --}}
    <select name="month" class="border p-2 rounded">
        <option value="">All Months</option>
        @for($m = 1; $m <= 12; $m++)
            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                {{ date("F", mktime(0, 0, 0, $m, 1)) }}
            </option>
        @endfor
    </select>

    {{-- Filter by Year --}}
    <select name="year" class="border p-2 rounded">
        <option value="">All Years</option>
        @for($y = 2023; $y <= now()->year; $y++)
            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
        @endfor
    </select>

    {{-- Filter by Agency --}}
    <select name="agency_id" class="border p-2 rounded">
        <option value="">All Agencies</option>
        @foreach($agencies as $agency)
            <option value="{{ $agency->id }}" {{ $agencyId == $agency->id ? 'selected' : '' }}>
                {{ $agency->agency_name ?? 'Unnamed Agency' }}
            </option>
        @endforeach
    </select>

    {{-- Buttons --}}
    <div class="flex gap-3 mt-6">
    <button type="submit"
            class="bg-black text-white font-semibold px-4 py-2 rounded hover:bg-gray-800 transition">
        Apply
    </button>

    <a href="{{ route('mcmc.assignment-report.pdf', request()->all()) }}"
       class="bg-black text-white font-semibold px-4 py-2 rounded hover:bg-gray-800 transition">
        Download PDF
    </a>

    <a href="{{ route('mcmc.assignment-report.pdf', request()->all()) }}"
       class="bg-green-600 text-white font-semibold px-4 py-2 rounded hover:bg-green-800 transition">
        Download Excel
    </a>
</div>

</form>

{{-- 📊 Chart Section --}}
<canvas id="inquiryChart" height="100" class="mb-8"></canvas>

{{-- 📋 Table Section --}}
<table class="w-full bg-white shadow-md rounded-lg overflow-hidden text-sm">
    <thead class="bg-gray-200">
        <tr>
            <th class="p-3 text-left">Agency</th>
            <th class="p-3 text-left">Month</th>
            <th class="p-3 text-left">Year</th>
            <th class="p-3 text-left">Total Inquiries</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reportData as $row)
            <tr class="border-b">
                <td class="p-3">{{ $row->agency_name }}</td>
                <td class="p-3">{{ date('F', mktime(0, 0, 0, $row->month, 1)) }}</td>
                <td class="p-3">{{ $row->year }}</td>
                <td class="p-3">{{ $row->total_inquiries }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('inquiryChart');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($reportData->pluck('agency_name')),
            datasets: [{
                label: 'Total Inquiries Assigned',
                data: @json($reportData->pluck('total_inquiries')),
                backgroundColor: 'rgba(59, 130, 246, 0.7)'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
