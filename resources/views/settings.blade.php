@extends('layouts.dashboard')

@section('title', 'Notifications')

@section('content')
    <h2 class="section-title">Notifications</h2>

    <div class="card mt-4">
        <div class="flex justify-between items-center mb-2">
            <h2 class="text-lg font-bold">
                Notifications
                <span class="bg-red-600 text-white px-2 py-1 rounded-full text-xs">
                    {{ auth()->user()->unreadNotifications->count() }} unread
                </span>
            </h2>

            @if (auth()->user()->unreadNotifications->count())
                <a href="{{ route('notifications.markRead') }}"
                   class="text-sm text-blue-600 hover:underline">
                   Mark all as read
                </a>
            @endif
        </div>

        @forelse (auth()->user()->notifications as $notification)
            <div class="border-b py-3 px-2 {{ $notification->read_at ? 'bg-white' : 'bg-yellow-50' }}">
                <p class="mb-1">
                    📢 <strong>Notifications</strong>
                        <span class="bg-red-600 text-white px-2 py-1 rounded-full text-xs">
                            {{ auth()->user()->unreadNotifications->count() }} unread
                        </span>
                </p>
                <p class="text-sm italic text-gray-700 mb-1">
                    {{ $notification->data['message'] ?? 'Check the latest update on your inquiry.' }}
                </p>
                <small class="text-gray-500">
                    Received {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                </small>
            </div>
        @empty
            <p class="text-gray-600">You have no notifications.</p>
        @endforelse
    </div>
@endsection
