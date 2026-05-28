@extends('layouts.dashboard')

@section('title', 'Inquiry Details')

@section('content')
<div class="card">
    <h2 class="section-title mb-4">Inquiry Details</h2>

    <div class="mb-4">
        <p><strong>Subject:</strong> {{ $inquiry->subject }}</p>
        <p><strong>Submitted by:</strong> {{ $inquiry->full_name }} ({{ $inquiry->email }})</p>
        <p><strong>Message:</strong> {{ $inquiry->message }}</p>
        <p><strong>Status:</strong> {{ $inquiry->status }}</p>
        <p><strong>Investigation Status:</strong> {{ $inquiry->investigation_status ?? 'Not updated' }}</p>
        <p><strong>Assigned by MCMC:</strong> {{ optional($inquiry->assignment->mcmcStaff)->name ?? '-' }}</p>
        <p><strong>Assigned At:</strong> {{ optional($inquiry->assignment)->assigned_at ? \Carbon\Carbon::parse($inquiry->assignment->assigned_at)->format('d M Y') : '-' }}</p>

        <p><strong>Reviewing Officer:</strong> {{ $inquiry->reviewed_by ?? 'Not Updated' }}</p>

        <p><strong>Supporting Document:</strong>
            @if ($inquiry->supporting_document)
                <a href="{{ asset('storage/' . $inquiry->supporting_document) }}" target="_blank" class="text-blue-600 underline">View Document</a>
            @else
                Not Updated
            @endif
        </p>
    </div>
</div>

<div class="card mt-6">
    <h3 class="text-lg font-semibold mb-4">Jurisdiction Assessment & Decision</h3>

    <form method="POST" action="{{ route('agency.inquiries.progress.store', $inquiry->id) }}" >
        <!-- @method('PUT') -->
        @csrf
        {{-- Review Notes --}}
        <div class="mb-4">
            <label class="block mb-2 font-semibold">Review Notes</label>
            <textarea name="investigation_status" class="w-full border px-3 py-2 rounded" rows="4" required>{{ old('investigation_status', $inquiry->investigation_status) }}</textarea>
        </div>

        {{-- Reviewing Officer Name --}}
        <div class="mb-4">
            <label class="block mb-2 font-semibold">Reviewing Officer Name</label>
            <input 
                type="text" 
                name="reviewed_by" 
                class="w-full border px-3 py-2 rounded bg-gray-100" 
                value="{{ Auth::user()->name }}" 
                readonly>
        </div>



        {{-- Supporting Document --}}
        <div class="mb-4">
            <label class="block mb-2 font-semibold">Supporting Document (optional)</label>
            <input 
                type="file" 
                name="supporting_document" 
                class="w-full border px-3 py-2 rounded" 
                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
        </div>

        {{-- Final Status --}}
        <div class="mb-4">
            <label class="block mb-2 font-semibold">Final Status</label>
            <select name="final_status" class="w-full border px-3 py-2 rounded" required>
                <option value="Pending" {{ $inquiry->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Approved" {{ $inquiry->status == 'Approved' ? 'selected' : '' }}>Approved (Within Jurisdiction)</option>
                <option value="Fake" {{ $inquiry->status == 'Fake' ? 'selected' : '' }}>Identified as Fake</option>
                <option value="Rejected" {{ $inquiry->status == 'Rejected' ? 'selected' : '' }}>Rejected (Outside Jurisdiction)</option>
            </select>
        </div>

        <button type="submit" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800">
            Submit Review
        </button>
    </form>
</div>
@endsection
