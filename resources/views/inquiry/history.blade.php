@extends('layouts.dashboard')

@section('title', 'Inquiry History')

@section('content')
    <h2 class="section-title">Inquiry History</h2>

    <div class="card">
        @if ($inquiries->isEmpty())
            <p class="text-gray-600">You haven't submitted any inquiries yet.</p>
        @else
            <table class="w-full text-sm border">
                <thead class="bg-blue-400 text-left">
                    <tr>
                        <th class="p-3 border-b">Subject</th>
                        <th class="p-3 border-b">Date Assigned</th>
                        <th class="p-3 border-b">Status</th>
                        <th class="p-3 border-b">Description</th>
                        <th class="p-3 border-b">Reviewed By</th>
                        <th class="p-3 border-b">Supporting Document</th>
                        <th class="p-3 border-b">Updated</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($inquiries as $inquiry)
                        @php
                            $isAssigned  = optional($inquiry->assignment)->agency !== null;
                            $status      = $isAssigned ? ($inquiry->status ?? 'Pending') : 'Pending';
                            $notes       = $inquiry->investigation_status ?? '-';
                            $reviewedBy  = optional(optional($inquiry->assignment)->agency)->agency_name ?? '-';
                            
                            $reviewedAt  = $inquiry->status_updated_at 
                                ? \Carbon\Carbon::parse($inquiry->status_updated_at)->format('d M Y h:i A') 
                                : '-';

                            $statusColor = match ($status) {
                                'Approved'             => 'bg-green-100 text-green-800',
                                'Identified as Fake'   => 'bg-yellow-100 text-yellow-800',
                                'Rejected'             => 'bg-red-100 text-red-800',
                                'Pending'              => 'bg-indigo-100 text-indigo-800',
                                default                => 'bg-gray-200 text-gray-700',
                            };
                        @endphp

                        <tr class="border-t">
                            <td class="p-3">{{ $inquiry->subject }}</td>
                            <td class="p-3">
                                {{ optional($inquiry->assignment)->assigned_at 
                                    ? \Carbon\Carbon::parse($inquiry->assignment->assigned_at)->format('d M Y') 
                                    : '—' }}
                            </td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                                    {{ $status }}
                                </span>
                            </td>
                            <td class="p-3">
                                <span class="text-sm text-gray-800 italic">
                                     {{ $notes }}
                                </span>
                            </td>
                            <td class="p-3">{{ $reviewedBy }}</td>

                             {{-- ✅ Supporting Document Column --}}
                            <td class="p-3">
                                @if ($inquiry->supporting_document)
                                    <a href="{{ asset('storage/' . $inquiry->supporting_document) }}" 
                                    target="_blank" 
                                    class="text-blue-600 underline hover:text-blue-800">
                                        View
                                    </a>
                                @else
                                    <span class="text-gray-500 italic">-</span>
                                @endif
                            </td>           

                            <td class="p-3">{{ \Carbon\Carbon::parse($inquiry->created_at)->format('d M Y h:i A') }}</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    </div> <!-- close card -->
        <div class="mt-6 text-right">
        <a href="{{ route('dashboard.public') }}" class="inline-block bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">
            ← Back to Home
    </a>
</div>

@endsection
