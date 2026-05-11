@extends('layouts.dashboard')

@section('title', 'Agency Dashboard')

@section('content')
    <h2 class="section-title">Welcome Agency Staff!</h2>
    <div class="card">
        <p>This is your dashboard content.</p>
    </div>
@endsection

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Dashboard')</title>

    <style>
        /*----------------------------
        1) RESET / GLOBAL
        ----------------------------*/
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body, html {
            height: 100%;
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            color: #333;
        }
        a {
            text-decoration: none;
            color: inherit;
        }
        ul {
            list-style: none;
        }

        /*----------------------------
        2) HEADER
        ----------------------------*/
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            background-color: #10B981; /* green (formerly blue #3B82F6) */
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            z-index: 1000;
        }
        header h1 {
            font-size: 1.5rem;
            font-weight: bold;
        }
        header .header-icons {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        header .header-icons img {
            width: 24px;
            height: 24px;
            cursor: pointer;
            opacity: 0.8;
        }
        header .header-icons img:hover {
            opacity: 1;
        }

        /*----------------------------
        3) SIDEBAR
        ----------------------------*/
        aside {
            position: fixed;
            top: 60px; /* below header */
            left: 0;
            bottom: 0;
            width: 200px;
            background-color: #047857; /* darker green (formerly #2563EB) */
            color: white;
            display: flex;
            flex-direction: column;
        }
        aside nav {
            flex: 1;
            overflow-y: auto;
        }
        aside nav ul {
            margin-top: 20px;
        }
        aside nav li {
            margin-bottom: 10px;
        }
        aside nav a {
            display: block;
            padding: 12px 20px;
            font-size: 1rem;
            font-weight: 500;
            border-left: 4px solid transparent;
            transition: background-color 0.2s, border-left-color 0.2s;
        }
        aside nav a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        aside nav a.active {
            background-color: rgba(255, 255, 255, 0.2);
            border-left-color: #059669; /* medium‐dark green */
        }
        aside .sidebar-footer {
            padding: 20px;
        }
        aside .sidebar-footer form button {
            width: 100%;
            padding: 10px;
            background-color: #DC2626; /* red (Logout button unchanged) */
            border: none;
            color: white;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.2s;
        }
        aside .sidebar-footer form button:hover {
            background-color: #B91C1C;
        }

        /*----------------------------
        4) MAIN CONTENT
        ----------------------------*/
        main {
            margin-top: 60px;   /* below header */
            margin-left: 240px; /* beside sidebar */
            padding: 20px;
            min-height: calc(100% - 60px);
        }
        .container {
            margin: 0;        /* flush left under “main” */
            padding-left: 20px;
        }

        /*----------------------------
        5) CARDS & HEADINGS
        ----------------------------*/
        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            padding: 50px;
            width: 95%;
        }
        .card + .card {
            margin-top: 20px;
        }
        h2.section-title {
            font-size: 2rem;
            color: #059669; /* green (formerly #007acc) */
            margin-bottom: 16px;
            font-weight: bold;
        }

        /*----------------------------
        6) PROFILE AVATAR
        ----------------------------*/
        .avatar-wrapper {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid #059669; /* green (formerly #007acc) */
            overflow: hidden;
            margin-bottom: 16px;
        }
        .avatar-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /*----------------------------
        7) RECENT ACTIVITIES
        ----------------------------*/
        .activity-list {
            margin-top: 8px;
        }
        .activity-item {
            background-color: #f7f7f7;
            border-radius: 4px;
            padding: 10px 12px;
            margin-bottom: 8px;
        }
        .activity-item .activity-text {
            font-size: 0.95rem;
            color: #555;
        }

        /*----------------------------
        8) BUTTONS
        ----------------------------*/
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #059669; /* green (formerly #007acc) */
            color: white;
            font-weight: 600;
            text-align: center;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .btn:hover {
            background-color: #34D399; /* lighter green (formerly #89CFF0) */
        }
    </style>

    @yield('head')
</head>
<body>
    <!-- HEADER -->
    <header>
        <h1>Welcome to MySebenarnya</h1>
        <div class="header-icons">
            {{-- Replace with a real bell‐icon image or SVG --}}
            <img src="https://cdn.jsdelivr.net/npm/feather-icons/dist/icons/bell.svg" alt="Notifications" />
        </div>
    </header>

    <!-- SIDEBAR -->
    <aside>
        <nav>
            <ul>
                <li>
                    @php
                        $user = Auth::user();
                        // Figure out which route name to use:
                        if ($user->role === 'agency_staff' && ! $user->is_first_login) {
                            $homeRoute = 'dashboard.agency';
                        } elseif ($user->role === 'mcmc_staff') {
                            $homeRoute = 'dashboard.mcmc';
                        } else {
                            $homeRoute = 'dashboard.public';
                        }
                    @endphp

                    <a href="{{ route($homeRoute) }}"
                       class="{{ request()->routeIs($homeRoute) ? 'active' : '' }}">
                       Home
                    </a>
                </li>
                <li>
                    <a 
                        href="{{ route('profile') }}" 
                        class="{{ request()->routeIs('profile') ? 'active' : '' }}"
                    >
                        Profile
                    </a>
                </li>
                <li>
                    <a 
                        href="{{ route('settings') }}" 
                        class="{{ request()->routeIs('settings') ? 'active' : '' }}"
                    >
                        Settings
                    </a>
                </li>
            </ul>
        </nav>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main>
        <div class="container">
            @yield('content')
        </div>
    </main>

    @yield('scripts')
</body>
</html>
