@extends('layouts.app')

@section('title', 'Verify Email')

@section('content')
    <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-lg text-center">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Verify Your Email</h2>
        
        {{-- ATTRIBUTE: verification_status (implied session/flash) --}}
        <p class="text-gray-700 mb-6">A verification code has been sent to your email. Please check your inbox and click the button below to enter your code.</p>
        
        {{-- METHOD: goToVerificationForm() --}}
        <a
            href="{{ route('verification.form') }}"
            class="inline-block bg-blue-500 text-white font-semibold px-6 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400"
        >
            Enter Verification Code
        </a>
    </div>
@endsection
