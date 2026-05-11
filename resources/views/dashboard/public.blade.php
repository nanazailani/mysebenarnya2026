@extends('layouts.dashboard')

@section('title', 'Public Dashboard')

@section('content')
    <h2 class="section-title">Public Dashboard</h2>

    <div class="card mb-6">
        <p>Welcome, {{ Auth::user()->name }}! You’re on the public dashboard.</p>
    </div>

    <div class="card">
        <h3 class="text-lg font-bold mb-4">Inquiry Tracking</h3>

        @if ($inquiries->isEmpty())
            <p class="text-gray-600">You haven't submitted any inquiries yet.</p>
        @else
            <table class="w-full text-sm border">
                <thead class="bg-blue-400 text-left">
                    <tr>
                        <th class="p-3 border-b">Subject</th>
                        <th class="p-3 border-b">Assigned Agency</th>
                        <th class="p-3 border-b">Date Assigned</th>
                        <th class="p-3 border-b">Status</th>
                        <th class="p-3 border-b">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($inquiries as $inquiry)
                        @php
                            $isAssigned = optional($inquiry->assignment)->agency !== null;
                            $status = $isAssigned ? ($inquiry->status ?? 'Pending') : 'Pending';

                            $badgeColor = match ($status) {
                                'Approved'     => 'bg-green-100 text-green-800',
                                'Fake'   => 'bg-yellow-100 text-yellow-800',
                                'Rejected'             => 'bg-red-100 text-red-800',
                                'Under Investigation'  => 'bg-indigo-100 text-indigo-800',
                                default                => 'bg-gray-200 text-gray-700',
                            };
                        @endphp
                        <tr class="border-t">
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
                                <a href="{{ route('inquiry.history', ['id' => $inquiry->id]) }}" class="text-blue-600 underline">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
