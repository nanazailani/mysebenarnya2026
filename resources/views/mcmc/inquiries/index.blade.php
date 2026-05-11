@extends('layouts.dashboard')

@section('title', 'Manage Inquiries')

@section('content')
<h2 class="section-title">Manage Inquiries</h2>

<form method="GET" action="{{ route('mcmc.inquiries') }}" class="mb-4 flex items-center gap-4">
    <label for="status">Filter by Status:</label>
    <select name="status" id="status" class="border rounded p-2">
        <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All</option>
        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
    </select>

    <label for="sort">Sort by Date:</label>
    <select name="sort" id="sort" class="border rounded p-2">
        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest First</option>
        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
    </select>

    <button type="submit" class="btn">Apply</button>

    <a href="{{ route('mcmc.inquiries.pdf', request()->query()) }}"
        class="btn bg-green-600 text-white px-4 py-2 rounded">
        Download PDF
    </a>
</form>

<div class="bg-white shadow rounded-lg p-6 mt-6">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-100 text-xs font-semibold text-gray-700 uppercase">
            <tr>
                <th class="px-4 py-3 text-left">Id</th>
                <th class="px-4 py-3 text-left">Subject</th>
                <th class="px-4 py-3 text-left">Full Name</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">Rejection Note</th>
                <th class="px-4 py-3 text-left">Submitted At</th>
                <th class="px-4 py-3 text-center">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 text-sm text-gray-800">
            @forelse ($inquiries as $index => $inquiry)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                    <td class="px-4 py-2 max-w-sm">{{ $inquiry->subject }}</td>
                    <td class="px-4 py-2">{{ $inquiry->full_name }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 text-xs rounded-full font-medium
                            {{ $inquiry->status === 'Approved' ? 'bg-green-100 text-green-800' :
                               ($inquiry->status === 'Rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($inquiry->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-2">
                        @if($inquiry->status === 'Rejected')
                            {{ $inquiry->investigation_status ?? 'No note' }}
                        @else
                            —
                        @endif
                    </td>
                    <td class="px-4 py-2">{{ $inquiry->created_at->format('d M Y, h:i A') }}</td>
                    <td class="px-4 py-2 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <a href="{{ route('mcmc.inquiries.show', $inquiry->id) }}"
                               class="bg-black text-white px-4 py-1 rounded hover:bg-gray-800 text-xs font-semibold w-24 text-center">
                                View
                            </a>

                            @if($inquiry->status === 'Rejected')
                                <a href="{{ route('mcmc.assign-form', ['inquiry_id' => $inquiry->id]) }}"
                                   class="bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700 text-xs font-semibold w-24 text-center">
                                    Reassign
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-6 text-gray-500">No inquiries found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
