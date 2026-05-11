@extends('layouts.dashboard')

@section('title', 'MCMC Dashboard')

@section('content')
    <h2 class="section-title" style="color: #000000; margin-bottom:16px;">
        MCMC Staff Dashboard
    </h2>

    <div class="card">
        <h3 class="text-lg font-semibold mb-3 uppercase">Inquiry Investigation Progress</h3>

        @if($inquiries->isEmpty())
            <p class="text-gray-600">No inquiries assigned to agencies yet.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm border">
                    <thead class="bg-gray-100  uppercase">
                        <tr>
                            <th class="p-3 border-b">ID</th>
                            <th class="p-3 border-b">Subject</th>
                            <th class="p-3 border-b">Submitted By</th>
                            <th class="p-3 border-b">Assigned Agency</th>
                            <th class="p-3 border-b">Status</th>
                            <th class="p-3 border-b">Description</th>
                            <th class="p-3 border-b">Updated At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inquiries as $index => $inquiry)
                            @php
                                $statusColor = match($inquiry->status) {
                                    'Approved' => 'bg-green-100 text-green-800',
                                    'Fake'     => 'bg-pink-400 text-yellow-800',
                                    'Rejected' => 'bg-red-400 text-red-800',
                                    'Pending'  => 'bg-gray-400 text-blue-800',
                                    default    => 'bg-gray-100 text-gray-700',
                                };
                            @endphp
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $index + 1 }}</td>
                                <td class="p-3">{{ $inquiry->subject }}</td>
                                <td class="px-4 py-2">{{ $inquiry->full_name }}</td>
                                <td class="p-3">{{ optional(optional($inquiry->assignment)->agency)->agency_name ?? '—' }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                                        {{ $inquiry->status ?? 'Pending' }}
                                    </span>
                                </td>
                                <td class="p-3 italic text-sm">
                                    {{ $inquiry->jurisdiction_notes ?? '-' }}
                                </td>
                                <td class="p-3">
                                    {{ $inquiry->status_updated_at
                                        ? \Carbon\Carbon::parse($inquiry->status_updated_at)->format('d M Y, h:i A')
                                        : '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
