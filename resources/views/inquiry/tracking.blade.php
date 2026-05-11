@extends('layouts.dashboard')

@section('title', 'Inquiry Tracking')

@section('content')
    <h2 class="section-title mb-4">INQUIRY TRACKING</h2>

    @if ($inquiries->isEmpty())
        <p>You haven’t submitted any inquiries yet.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full bg-white shadow-md rounded text-sm">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-3 text-left">Subject</th>
                        <th class="p-3 text-left">Agency</th>
                        <th class="p-3 text-left">Date Assigned</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($inquiries as $inquiry)
                        @php
                            $isAssigned = optional($inquiry->assignment)->agency !== null;
                            $status = $isAssigned ? ($inquiry->status ?? 'Pending') : 'Pending';

                            $badgeColor = match ($status) {
                                'Approved'             => 'bg-green-200 text-green-800',
                                'Fake'                 => 'bg-gray-400 text-white',
                                'Rejected'             => 'bg-red-200 text-red-800',
                                'Pending'              => 'bg-yellow-200 text-yellow-800',
                                default                => 'bg-gray-200 text-gray-700',
                            };
                        @endphp

                        <tr class="border-b">
                            <td class="p-3">{{ $inquiry->subject }}</td>
                            <td class="p-3">
                                {{ optional(optional($inquiry->assignment)->agency)->agency_name ?? 'Not assigned yet' }}
                            </td>
                            <td class="p-3">
                                {{ optional($inquiry->assignment)->assigned_at 
                                    ? \Carbon\Carbon::parse($inquiry->assignment->assigned_at)->format('d M Y') 
                                    : '—' }}
                            </td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $badgeColor }}">
                                    {{ $status }}
                                </span>
                            </td>
                            <td class="p-3">
                                <a href="{{ route('inquiry.progress.show', $inquiry->id) }}"
                                   class="text-blue-600 font-medium hover:underline">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- pagination --}}
        <div class="mt-6 flex justify-center">
            {{ $inquiries->links() }}
            
        </div>
        
    @endif
@endsection
