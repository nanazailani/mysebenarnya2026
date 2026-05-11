{{-- resources/views/profile/edit.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Edit Profile')

@php
    $user = Auth::user();
@endphp

@section('content')
    {{-- 1) Page Heading (outside the card) --}}
    <h2 class="section-title" style="
        font-size: 2.5rem; 
        margin-bottom: 24px; 
        font-weight: 700;
        /* color comes from layout (green for agency, black for MCMC, blue for public) */
    ">
        Edit Profile
    </h2>

    {{-- 2) Card Container --}}
    <div class="card" style="
    width: 95%;
    margin: 0 0 40px 10px; /* top=0, right=0, bottom=40px, left=20px */
    padding: 30px 40px;
">
        <form 
        {{-- determineRoute() Methods --}}
            method="POST"
            action="{{ 
                $user->role === 'agency_staff' 
                    ? route('agency.profile.update') 
                    : ($user->role === 'mcmc_staff' 
                        ? route('mcmc.profile.update') 
                        : route('profile.update')) 
            }}"
            enctype="multipart/form-data"
        >
            @csrf
            @method('PUT')

            {{-- 1) Name --}}
            <div style="margin-bottom: 20px;">
                <label for="name" style="
                    display: block;
                    margin-bottom: 6px;
                    color: #374151;
                    font-size: 1.125rem;  /* larger label font */
                    font-weight: 600;
                ">
                    Name
                </label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    {{-- displayOldValues() Methods --}}
                    value="{{ old('name', $user->name) }}"
                    required
                    style="
                        width: 100%;
                        padding: 12px;
                        border: 1px solid #D1D5DB;
                        border-radius: 6px;
                        font-size: 1rem;
                    "
                    class="focus:outline-none focus:ring-2 {{ $user->role === 'agency_staff' ? 'focus:ring-green-500' : ($user->role === 'mcmc_staff' ? 'focus:ring-black' : 'focus:ring-blue-500') }}"
                >
                {{-- displayErrors() Methods --}}
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
                    Email
                </label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email', $user->email) }}"
                    required
                    style="
                        width: 100%;
                        padding: 12px;
                        border: 1px solid #D1D5DB;
                        border-radius: 6px;
                        font-size: 1rem;
                    "
                    class="focus:outline-none focus:ring-2 {{ $user->role === 'agency_staff' ? 'focus:ring-green-500' : ($user->role === 'mcmc_staff' ? 'focus:ring-black' : 'focus:ring-blue-500') }}"
                >
                @error('email')
                    <p style="color:#DC2626; font-size:0.875rem; margin-top:6px;">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- 3) Contact --}}
            <div style="margin-bottom: 20px;">
                <label for="contact" style="
                    display: block;
                    margin-bottom: 6px;
                    color: #374151;
                    font-size: 1.125rem;
                    font-weight: 600;
                ">
                    Contact
                </label>
                <input
                    id="contact"
                    name="contact"
                    type="text"
                    value="{{ old('contact', $user->contact) }}"
                    style="
                        width: 100%;
                        padding: 12px;
                        border: 1px solid #D1D5DB;
                        border-radius: 6px;
                        font-size: 1rem;
                    "
                    class="focus:outline-none focus:ring-2 {{ $user->role === 'agency_staff' ? 'focus:ring-green-500' : ($user->role === 'mcmc_staff' ? 'focus:ring-black' : 'focus:ring-blue-500') }}"
                >
                @error('contact')
                    <p style="color:#DC2626; font-size:0.875rem; margin-top:6px;">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- 4) Agency Name (if this column exists) --}}
            @if(isset($user->agency_name))
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
                        value="{{ old('agency_name', $user->agency_name) }}"
                        style="
                            width: 100%;
                            padding: 12px;
                            border: 1px solid #D1D5DB;
                            border-radius: 6px;
                            font-size: 1rem;
                        "
                        class="focus:outline-none focus:ring-2 {{ $user->role === 'agency_staff' ? 'focus:ring-green-500' : ($user->role === 'mcmc_staff' ? 'focus:ring-black' : 'focus:ring-blue-500') }}"
                    >
                    @error('agency_name')
                        <p style="color:#DC2626; font-size:0.875rem; margin-top:6px;">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            @endif

            {{-- 5) Avatar Upload --}}
            <div style="margin-bottom: 20px;">
                <label for="avatar" style="
                    display: block;
                    margin-bottom: 6px;
                    color: #374151;
                    font-size: 1.125rem;
                    font-weight: 600;
                ">
                    Avatar
                </label>
                
                <input
                 {{-- uploadAvatar() Methods --}}
                    id="avatar"
                    name="avatar"
                    type="file"
                    accept="image/*"
                    style="
                        width: 100%;
                        padding: 8px;
                        border: 1px solid #D1D5DB;
                        border-radius: 6px;
                        font-size: 1rem;
                    "
                    class="focus:outline-none focus:ring-2 {{ $user->role === 'agency_staff' ? 'focus:ring-green-500' : ($user->role === 'mcmc_staff' ? 'focus:ring-black' : 'focus:ring-blue-500') }}"
                >
                @error('avatar')
                    <p style="color:#DC2626; font-size:0.875rem; margin-top:6px;">
                        {{ $message }}
                    </p>
                @enderror

                @if($user->avatar)
                    <p class="mt-2" style="font-size:0.875rem; color:#6B7280;">
                        Current Avatar:
                        <img 
                            src="{{ asset('storage/' . $user->avatar) }}" 
                            alt="Avatar" 
                            style="
                                display: inline-block; 
                                vertical-align: middle; 
                                width: 40px; 
                                height: 40px; 
                                border-radius: 50%; 
                                object-fit: cover; 
                                margin-left: 8px;
                            "
                        >
                    </p>
                @endif
            </div>

            {{-- 6) New Password --}}
            <div style="margin-bottom: 20px;">
                <label for="password" style="
                    display: block;
                    margin-bottom: 6px;
                    color: #374151;
                    font-size: 1.125rem;
                    font-weight: 600;
                ">
                    New Password
                    <small style="font-size:0.875rem; color:#6B7280; font-weight:400;">
                        (leave blank if you don’t want to change)
                    </small>
                </label>
                <input
                {{-- updatePassword() Methods --}}
                    id="password"
                    name="password"
                    type="password"
                    autocomplete="new-password"
                    style="
                        width: 100%;
                        padding: 12px;
                        border: 1px solid #D1D5DB;
                        border-radius: 6px;
                        font-size: 1rem;
                    "
                    class="focus:outline-none focus:ring-2 {{ $user->role === 'agency_staff' ? 'focus:ring-green-500' : ($user->role === 'mcmc_staff' ? 'focus:ring-black' : 'focus:ring-blue-500') }}"
                >
                @error('password')
                    <p style="color:#DC2626; font-size:0.875rem; margin-top:6px;">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- 7) Confirm Password --}}
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
                    autocomplete="new-password"
                    style="
                        width: 100%;
                        padding: 12px;
                        border: 1px solid #D1D5DB;
                        border-radius: 6px;
                        font-size: 1rem;
                    "
                    class="focus:outline-none focus:ring-2 {{ $user->role === 'agency_staff' ? 'focus:ring-green-500' : ($user->role === 'mcmc_staff' ? 'focus:ring-black' : 'focus:ring-blue-500') }}"
                >
            </div>

            {{-- 8) Save Changes Button --}}
            <div style="text-align: center;">
                <button
                    type="submit"
                    class="btn"
                    style="
                        padding: 12px 30px; 
                        font-size: 1.125rem; 
                        font-weight: 600;
                        border-radius: 6px;
                    "
                >
                    Save Changes
                </button>
            </div>
        </form>
    </div>
@endsection
