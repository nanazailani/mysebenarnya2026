
@extends('layouts.app')

@section('title', 'Login')

@section('content')
    {{-- Flex container: full screen, align child at start (top), add vertical padding --}}
    <div class="flex justify-center items-start h-screen pt-24">
        {{-- The login card --}}
        <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Login</h2>

            {{-- METHOD: displayErrors() --}}
            @if(session('status'))
                <div class="mb-4 text-green-600 text-center">
                    {{ session('status') }} {{-- ATTRIBUTE: status --}}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
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
                    @error('email') {{-- ATTRIBUTE: error_message --}}
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                 <!-- ATTRIBUTE: password -->
                <!-- METHOD: validateInput() -->
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>

                    <div class="relative">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                        >
                
                        <!-- 👁️ Eye Icon --> 
                        <span onclick="togglePassword()"
                        class="absolute right-3 top-2.5 cursor-pointer text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </span>
                </div>
                
                @error('password') {{-- ATTRIBUTE: error_message --}}
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
          

                <!-- ATTRIBUTE: remember -->
                <!-- METHOD: rememberUser() -->
                <div class="mb-4 flex items-center">
                    <input
                        id="remember"
                        type="checkbox"
                        name="remember"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-400 border-gray-300 rounded"
                    >
                    <label for="remember" class="ml-2 block text-gray-700">Remember Me</label>
                </div>

                <!-- METHOD: redirectToDashboard() -->
                <div class="mt-6">
                    <button
                        type="submit"
                        class="w-full bg-blue-500 text-white font-semibold px-4 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    >
                        Login
                    </button>


                <!-- Add this block for “Forgot Your Password?” -->
                <div class="mt-4 text-center">
                    <a 
                      href="{{ route('password.request') }}" 
                      class="text-blue-500 hover:underline"
                    >
                      Forgot Your Password?
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- SCRIPT -->
    <script>
    function togglePassword() {
        const pwd = document.getElementById("password");
        pwd.type = pwd.type === "password" ? "text" : "password";
    }
    </script>
    
@endsection
