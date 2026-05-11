@extends('layouts.dashboard')

@section('title', 'Track Inquiries')

@section('content')

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <h2 class="section-title mb-4">Track Inquiries</h2>

    {{-- Filter Form --}}
    <form method="GET" class="flex flex-wrap gap-4 mb-6">
        <select name="status" class="border px-3 py-2 rounded">
            <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Statuses</option>
            <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
            <option value="Approved" {{ request('status') === 'Approved' ? 'selected' : '' }}>Approved</option>
            <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
        </select>

        <input type="date" name="date_from" class="border px-3 py-2 rounded" placeholder="From Date"
               value="{{ request('date_from') }}">
        <input type="date" name="date_to" class="border px-3 py-2 rounded" placeholder="To Date"
               value="{{ request('date_to') }}">

        <input type="text" name="subject" class="border px-3 py-2 rounded" placeholder="Search Subject"
               value="{{ request('subject') }}">

        <button type="submit" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800 transition">
            Filter
        </button>
    </form>

    {{-- Inquiry Table --}}
    <table class="w-full table-auto border text-sm">
        <thead class="bg-gray-200">
            <tr>
                <th class="px-4 py-2">#</th>
                <th class="px-4 py-2">Subject</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Date Submitted</th>
                <th class="px-4 py-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($inquiries as $i => $inq)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $i + 1 }}</td>
                    <td class="px-4 py-2">{{ $inq->subject }}</td>
                    <td class="px-4 py-2">
                    @if ($inq->status == 'Pending')
                    <span class="bg-yellow-200 text-yellow-800 text-xs font-semibold px-2 py-1 rounded">Pending</span>
                    @elseif ($inq->status == 'Approved')
                    <span class="bg-green-200 text-green-800 text-xs font-semibold px-2 py-1 rounded">Approved</span>
                    @elseif ($inq->status == 'Rejected')
                    <span class="bg-red-200 text-red-800 text-xs font-semibold px-2 py-1 rounded">Rejected</span>
                    @elseif($inq->status == 'Fake')
                    <span class="bg-pink-400 text-gray-800 px-2 py-1 rounded">Fake</span>
                    @endif
                    </td>

                    <td class="px-4 py-2">{{ $inq->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ route('agency.inquiries.show', $inq->id) }}"
                           class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                            View
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-gray-500 py-4">No inquiries found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
