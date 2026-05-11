@extends('layouts.dashboard')

@section('title', 'Reassign Inquiry')

@section('content')
    <h2 class="section-title mb-4">Reassign Rejected Inquiry</h2>

    <div class="card">
        <p><strong>Subject:</strong> {{ $inquiry->subject }}</p>
        <p><strong>Submitted by:</strong> {{ $inquiry->full_name }} ({{ $inquiry->email }})</p>
        <p><strong>Status:</strong> {{ $inquiry->status }}</p>
        <p><strong>Previous Notes:</strong> {{ $inquiry->investigation_status }}</p>
    </div>

    <div class="card mt-6">
        <form method="POST" action="{{ route('mcmc.assign.update') }}">
            @csrf

            <input type="hidden" name="inquiry_id" value="{{ $inquiry->id }}">

            <div class="mb-4">
                <label class="block mb-2 font-semibold">Reassign to Agency</label>
                <select name="agency_id" class="w-full border px-3 py-2 rounded" required>
                    <option value="">Select an agency</option>
                    @foreach ($agencies as $agency)
                        <option value="{{ $agency->id }}">{{ $agency->agency_name }} ({{ $agency->email }})</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Reassign Inquiry
            </button>
        </form>
    </div>
@endsection
