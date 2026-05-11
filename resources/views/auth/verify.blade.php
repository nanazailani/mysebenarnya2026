@extends('layouts.app')

@section('title', 'Email Verification')

@section('content')
    <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Email Verification</h2>

        {{-- METHOD: verifyCode() --}}
        <form method="POST" action="{{ route('verification.verify') }}">
            @csrf

            <!-- ATTRIBUTE: email -->
            <!-- METHOD: validateInput() -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                >
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

             <!-- ATTRIBUTE: verification_code -->
            <!-- METHOD: validateInput() -->
            <div class="mb-4">
                <label for="verification_code" class="block text-gray-700 font-medium mb-2">Verification Code</label>
                <input
                    id="verification_code"
                    type="text"
                    name="verification_code"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                >
                @error('verification_code')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p> {{-- ATTRIBUTE: error_message --}}
                    @enderror
                @enderror
            </div>

           <!-- METHOD: verifyCode() -->
            <div class="mt-6">
                <button
                    type="submit"
                    class="w-full bg-blue-500 text-white font-semibold px-4 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400"
                >
                    Verify Email
                </button>
            </div>
        </form>
    </div>
@endsection
