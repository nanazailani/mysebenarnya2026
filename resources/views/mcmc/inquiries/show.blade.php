@extends('layouts.dashboard')

@section('title', 'Inquiry Details')

@section('content')
<div class="card">
    <h2 class="section-title">Inquiry Details</h2>

    <p><strong>Full Name:</strong> {{ $inquiry->full_name }}</p>
    <p><strong>Email:</strong> {{ $inquiry->email }}</p>
    <p><strong>Phone:</strong> {{ $inquiry->phone_number }}</p>
    <p><strong>Subject:</strong> {{ $inquiry->subject }}</p>
    <p><strong>Message:</strong> {{ $inquiry->message }}</p>
    <p><strong>Source Type:</strong> {{ $inquiry->source_type }}</p>
    <p><strong>Source URL:</strong> <a href="{{ $inquiry->source_url }}" target="_blank">{{ $inquiry->source_url }}</a></p>
    <p><strong>Status:</strong> {{ $inquiry->status }}</p>

    <form method="POST" action="{{ route('mcmc.inquiries.update', $inquiry->id) }}" class="mt-4">
        @csrf
        @method('PUT')
        <label for="status">Update Status:</label>
        <select name="status" id="status" class="border p-2">
            <option value="Pending" {{ $inquiry->status == 'Pending' ? 'selected' : '' }}>Pending</option>
            <option value="Approved" {{ $inquiry->status == 'Approved' ? 'selected' : '' }}>Approved</option>
            <option value="Rejected" {{ $inquiry->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
        <button type="submit" class="btn mt-2">Update</button>
    </form>
</div>
@endsection
