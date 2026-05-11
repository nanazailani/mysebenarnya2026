@extends('layouts.dashboard')

@section('title', 'Progress Report')

@section('content')
    <h2 class="section-title mb-6">Progress Report</h2>

    <div class="card mb-6 p-4">
        <form method="GET" action="{{ route('progress.report') }}" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium mb-1">From</label>
                <input type="date" name="from" class="border rounded px-3 py-1" value="{{ request('from') }}">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">To</label>
                <input type="date" name="to" class="border rounded px-3 py-1" value="{{ request('to') }}">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Agency</label>
                <select name="agency" class="border rounded px-3 py-1">
                    <option value="">All</option>
                    @isset($summary)
                        @foreach($summary->keys() as $agency)
                            <option value="{{ $agency }}" {{ request('agency') === $agency ? 'selected' : '' }}>
                                {{ $agency }}
                            </option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Filter
            </button>
        </form>
    </div>

    <div class="card p-4">
        <h3 class="text-lg font-semibold mb-4">Agency Performance Summary</h3>

        @if(isset($summary) && $summary->count())
            <table class="w-full text-sm border mb-4">
                <thead class="bg-gray-200 text-left">
                    <tr>
                        <th class="p-3 border">Agency</th>
                        <th class="p-3 border">Total Assigned</th>
                        <th class="p-3 border">Resolved</th>
                        <th class="p-3 border">Pending</th>
                        <th class="p-3 border">Avg Resolution Time (Days)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($summary as $agency => $data)
                        <tr class="border-t">
                            <td class="p-3">{{ $agency }}</td>
                            <td class="p-3">{{ $data['assigned'] }}</td>
                            <td class="p-3 text-green-700 font-medium">{{ $data['resolved'] }}</td>
                            <td class="p-3 text-yellow-700">{{ $data['pending'] }}</td>
                            <td class="p-3 text-blue-800">
                                {{ is_numeric($data['avg_days']) ? round($data['avg_days'], 1) . ' days' : 'N/A' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Export buttons --}}
            <div class="flex gap-4">
                <a href="{{ route('progress.report.pdf', request()->query()) }}"
                   class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                    Download PDF
                </a>
                <a href="{{ route('progress.report.excel', request()->query()) }}"
                   class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Download Excel
                </a>
            </div>
        @else
            <p class="text-gray-500 text-sm">No data available for the selected filters.</p>
        @endif
    </div>
@endsection
