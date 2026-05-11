@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
    <div class="flex justify-center items-start h-screen pt-24">
        <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Reset Password</h2>

            {{-- METHOD: resetPassword() --}}
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                {{-- ATTRIBUTE: token --}}
                <input type="hidden" name="token" value="{{ $token }}">

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
                        autofocus
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                    >
                    {{-- METHOD: displayErrors() --}}
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>{{-- ATTRIBUTE: error_message --}}
                    @enderror
                </div>

                <!-- ATTRIBUTE: password -->
                <!-- METHOD: validateInput(), validatePasswordStrength() -->
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-medium mb-2">New Password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                    >
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                 <!-- ATTRIBUTE: password_confirmation -->
                <!-- METHOD: validateInput() -->
                <div class="mb-4">
                    <label for="password_confirmation" class="block text-gray-700 font-medium mb-2">Confirm Password</label>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                    >
                </div>

                <!-- METHOD: resetPassword() -->
                <div class="mt-6">
                    <button
                        type="submit"
                        class="w-full bg-blue-500 text-white font-semibold px-4 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    >
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
