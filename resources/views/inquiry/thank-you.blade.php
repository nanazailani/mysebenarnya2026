@extends('layouts.dashboard')

@section('title', 'Thank You')

@section('content')
<div class="card">
    <h2 class="section-title text-green-700">Thank You for Your Submission</h2>
    <p class="mb-4">
        We truly appreciate the information you've submitted. Our team will review your inquiry
        to ensure that the Internet remains a space free from false or misleading content.
    </p>
    <a href="{{ route('dashboard.public') }}" class="btn btn-primary">Back to Dashboard</a>
</div>
@endsection

