@extends('layouts.dashboard')

@section('title', 'My Profile')

@section('content')

    {{-- STATUS MESSAGE --}}
    @if(session('status'))
        <div style="background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 12px 20px; margin-bottom: 20px; border-radius: 4px; font-weight: 500;">
            {{ session('status') }}
        </div>
    @endif

     {{-- PAGE HEADER --}}
    <h2 class="section-title">My Profile</h2>

    <div class="card" style="display: flex; flex-wrap: wrap; gap: 20px;">
        <!-- Avatar Section -->
        <div style="flex: 0 0 150px; text-align: center;">
            <div class="avatar-wrapper">
                <!-- displayAvatar() Methods -->
                @if($user->avatar)
                <img 
                src="{{ asset('storage/' . $user->avatar) }}" alt="User Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <img src="{{ asset('images/default-avatar.png') }}" alt="Default Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                @endif
            </div>
            
        </div>

        <!-- displayProfile() Methods -->
        <div style="flex: 1 1 300px;">
            <div style="margin-bottom: 24px;">
                <p><strong>Name:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Joined:</strong> {{ $user->created_at->format('F j, Y') }}</p>

                @if($user->contact)
                    <p><strong>Contact:</strong> {{ $user->contact }}</p>
                @endif

                @if($user->agency_name)
                    <p><strong>Agency:</strong> {{ $user->agency_name }}</p>
                @endif
            </div>

            <!-- showActivities() Methods -->
            <h3 class="section-title" style="font-size: 1.5rem; margin-bottom: 12px; font-weight: bold;">
                Recent Activities
            </h3>
            <div class="activity-list">
                <div class="activity-item">
                    <div class="activity-text">
                        Shared article: <em>"New Vaccination Laws Mandated for All Students"</em>
                    </div>
                </div>
            </div>

            <div style="margin-top: 24px; text-align: center;">
                @php
                    // routeToEditProfile(): Dynamically generates correct route based on user role
                    if ($user->role === 'agency_staff') {
                        $editRoute = 'agency.profile.edit';
                    } elseif ($user->role === 'mcmc_staff') {
                        $editRoute = 'mcmc.profile.edit';
                    } else {
                        $editRoute = 'profile.edit';
                    }
                @endphp
                <a href="{{ route($editRoute) }}" class="btn">Edit Profile</a>
            </div>
        </div>
    </div>
@endsection
