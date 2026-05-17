@extends('layouts.dashboard')

@section('title', 'Thank You')

@section('content')
<div class="card">
    <h2 class="section-title text-green-700">Thank You for Your Submission</h2>
    <p class="mb-4">
        We truly appreciate the information you've submitted. Our team will review your inquiry
        to ensure that the Internet remains a space free from false or misleading content.
    </p>

    {{--CR-INQ-02: Inquiry reference number added--}}
    @if(session('inquiry_id'))
        <div class="mt-4 mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg text-center">
            <p class="text-sm text-gray-600">Your Inquiry Reference Number:</p>
            <p class="text-xl font-bold text-blue-600 mt-1">
                #INQ-{{ str_pad(session('inquiry_id'), 4, '0', STR_PAD_LEFT) }}
            </p>
            <p class="text-xs text-gray-500 mt-2">
                Please save this reference number to track your inquiry.
            </p>
        </div>
    @endif

    <a href="{{ route('dashboard.public') }}" class="btn btn-primary">Back to Dashboard</a>
</div>
@endsection

