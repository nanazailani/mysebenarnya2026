{{-- resources/views/mcmc/users/create-account.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Create Agency Staff Account')

@section('content')
    <h2 class="section-title" style="
        font-size: 2.25rem;
        margin-bottom: 24px;
        font-weight: 700;
        color: #000;  {{-- using MCMC’s black theme --}}
    ">
        Create Agency Staff Account
    </h2>

     {{-- displayStatus(): Displays session flash success message --}}
    @if(session('status'))
        <div style="
            background-color: #d1fae5;
            border: 1px solid #10b981;
            color: #065f46;
            padding: 12px 20px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-weight: 500;
        ">
            {{ session('status') }}
        </div>
    @endif

    <div class="card" style="
        width: 95%;
        margin-bottom: 40px;
        padding: 30px 40px;
    ">
     {{-- createAccount(): Submits the form to store new agency staff --}}
        <form method="POST" action="{{ route('mcmc.store-account') }}">
            @csrf

            {{-- 1) Name --}}
            <div style="margin-bottom: 20px;">
                <label for="name" style="
                    display: block;
                    margin-bottom: 6px;
                    color: #374151;
                    font-size: 1.125rem;
                    font-weight: 600;
                ">
                    Full Name
                </label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name') }}"
                    required
                    style="
                        width: 100%;
                        padding: 12px;
                        border: 1px solid #D1D5DB;
                        border-radius: 6px;
                        font-size: 1rem;
                    "
                    class="focus:outline-none focus:ring-2 focus:ring-black"
                >
                {{-- displayErrors(): Show validation error for name --}}
                @error('name')
                    <p style="color:#DC2626; font-size:0.875rem; margin-top:6px;">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- 2) Email --}}
            <div style="margin-bottom: 20px;">
                <label for="email" style="
                    display: block;
                    margin-bottom: 6px;
                    color: #374151;
                    font-size: 1.125rem;
                    font-weight: 600;
                ">
                    Email Address
                </label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    style="
                        width: 100%;
                        padding: 12px;
                        border: 1px solid #D1D5DB;
                        border-radius: 6px;
                        font-size: 1rem;
                    "
                    class="focus:outline-none focus:ring-2 focus:ring-black"
                >
                @error('email')
                    <p style="color:#DC2626; font-size:0.875rem; margin-top:6px;">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- 3) Agency Name --}}
            <div style="margin-bottom: 20px;">
                <label for="agency_name" style="
                    display: block;
                    margin-bottom: 6px;
                    color: #374151;
                    font-size: 1.125rem;
                    font-weight: 600;
                ">
                    Agency Name
                </label>
                <input
                    id="agency_name"
                    name="agency_name"
                    type="text"
                    value="{{ old('agency_name') }}"
                    required
                    style="
                        width: 100%;
                        padding: 12px;
                        border: 1px solid #D1D5DB;
                        border-radius: 6px;
                        font-size: 1rem;
                    "
                    class="focus:outline-none focus:ring-2 focus:ring-black"
                >
                @error('agency_name')
                    <p style="color:#DC2626; font-size:0.875rem; margin-top:6px;">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- 4) Password & Confirmation --}}
            <div style="margin-bottom: 20px;">
                <label for="password" style="
                    display: block;
                    margin-bottom: 6px;
                    color: #374151;
                    font-size: 1.125rem;
                    font-weight: 600;
                ">
                    Password
                </label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    style="
                        width: 100%;
                        padding: 12px;
                        border: 1px solid #D1D5DB;
                        border-radius: 6px;
                        font-size: 1rem;
                    "
                    class="focus:outline-none focus:ring-2 focus:ring-black"
                >
                @error('password')
                    <p style="color:#DC2626; font-size:0.875rem; margin-top:6px;">
                        {{ $message }}
                    </p>
                @enderror
            </div>
            <div style="margin-bottom: 30px;">
                <label for="password_confirmation" style="
                    display: block;
                    margin-bottom: 6px;
                    color: #374151;
                    font-size: 1.125rem;
                    font-weight: 600;
                ">
                    Confirm Password
                </label>
                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    required
                    style="
                        width: 100%;
                        padding: 12px;
                        border: 1px solid #D1D5DB;
                        border-radius: 6px;
                        font-size: 1rem;
                    "
                    class="focus:outline-none focus:ring-2 focus:ring-black"
                >
            </div>

            {{-- 5) Submit Button: Triggers createAccount() --}}
            <div style="text-align: center;">
                <button
                    type="submit"
                    class="btn"
                    style="
                        background-color: #000;
                        color: #fff;
                        padding: 12px 30px;
                        font-size: 1.125rem;
                        font-weight: 600;
                        border-radius: 6px;
                        border: none;
                        cursor: pointer;
                    "
                >
                    Create Account
                </button>
            </div>
        </form>
    </div>
@endsection
