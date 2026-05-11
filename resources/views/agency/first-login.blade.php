@extends('layouts.app') 

@section('title', 'First-Time Password Setup')

@section('content')
<div class="flex justify-center items-start h-screen pt-24">
    
    {{-- FORM CARD --}}   
    <div class="w-full max-w-md bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-8 py-6">

                {{-- HEADER --}}
                <h2 class="text-2xl font-semibold text-gray-800 text-center mb-6">
                    First-Time Password Setup
                </h2>

                {{-- METHOD: validateForm() --}}
                @if ($errors->any())
                    <div class="mb-4 text-sm text-red-600">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li> {{-- Show validation error --}}
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- METHOD: showForm() --}}
                <form method="POST" action="{{ route('agency.first-login.update') }}">
                    @csrf

                    {{-- ATTRIBUTE: password --}}
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 text-sm font-medium mb-1">
                            New Password
                        </label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            autocomplete="new-password"
                            
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>

                   {{-- ATTRIBUTE: password_confirmation --}}
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-gray-700 text-sm font-medium mb-1">
                            Confirm Password
                        </label>
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            required
                            autocomplete="new-password"
                            
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>

                     {{-- METHOD: submitPassword() --}}
                    <button
                        type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                    >
                        Set Password &amp; Continue
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
