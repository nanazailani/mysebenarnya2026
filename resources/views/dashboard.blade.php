@extends('layouts.dashboard')

@section('title', 'Home')

@section('content')
    <div class="card">
        <h2 class="section-title">Dashboard</h2>

        <p style="font-size: 1.1rem; margin-bottom: 16px;">
            Welcome, <strong>{{ Auth::user()->name }}</strong>!
        </p>

        @php
            $unreadCount = Auth::user()->unreadNotifications->count();
        @endphp

        @if($unreadCount > 0)
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">You have {{ $unreadCount }} unread notification{{ $unreadCount > 1 ? 's' : '' }}.</strong>
                <span class="block sm:inline">Check your <a href="{{ route('settings') }}" class="underline font-semibold">Settings</a> to view inquiry updates.</span>
            </div>
        @endif
    </div>
@endsection
