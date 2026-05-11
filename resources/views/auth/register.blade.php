@extends('layouts.app')

@section('title', 'Register')

@section('content')
    {{-- Flex container: full-screen height, align at top, add vertical padding --}}
    <div class="flex justify-center items-start h-screen pt-24">
        {{-- Registration card --}}
        <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Register</h2>

            {{-- METHOD: register() --}}
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- ATTRIBUTE: name -->
                <!-- METHOD: validateForm() -->
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-medium mb-2">Name</label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                    >
                    @error('name') {{-- METHOD: displayErrors() --}} {{-- ATTRIBUTE: error_message --}}
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ATTRIBUTE: email -->
                <!-- METHOD: validateForm(), checkEmailUniqueness() -->
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
                    @error('email') {{-- ATTRIBUTE: error_message --}}
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                 <!-- ATTRIBUTE: password -->
                <!-- METHOD: validateForm(), validatePasswordStrength() -->
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                    >
                    @error('password') {{-- ATTRIBUTE: error_message --}}
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ATTRIBUTE: password_confirmation -->
                <!-- METHOD: validateForm() -->
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

                 <!-- Submit: triggers register() -->
                <div class="mt-6">
                    <button
                        type="submit"
                        class="w-full bg-blue-500 text-white font-semibold px-4 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    >
                        Register
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
