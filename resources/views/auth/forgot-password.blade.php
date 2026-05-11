@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
    <div class="flex justify-center items-start h-screen pt-24">
        <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Forgot Password</h2>

            {{-- METHOD: displayErrors() --}}
            @if (session('status'))
                <div class="mb-4 text-green-600 text-center">
                    {{ session('status') }}  {{-- ATTRIBUTE: status --}}
                </div>
            @endif

             {{-- METHOD: sendResetLink() --}}
            <form method="POST" action="{{ route('password.email') }}">
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
                        autofocus
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                    >
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p> {{-- ATTRIBUTE: error_message --}}
                    @enderror
                </div>

                <!-- METHOD: sendResetLink() -->
                <div class="mt-6">
                    <button
                        type="submit"
                        class="w-full bg-blue-500 text-white font-semibold px-4 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    >
                        Send Password Reset Link
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

