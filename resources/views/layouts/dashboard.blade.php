{{-- resources/views/layouts/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

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

            /* ←— Replace the hard-coded blue with a dynamic background */
            @php $r = Auth::check() ? Auth::user()->role : 'guest'; 
            $currentUser = Auth::user();@endphp
            background-color:
            {{ $r === 'agency_staff' ? '#10B981' :
            ($r === 'mcmc_staff' ? '#000000' : '#3B82F6') }}; /* blue-500 */

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
            /* If MCMC, invert icon color to white: */
            @if(Auth::user()->role === 'mcmc_staff')
                filter: invert(100%);
            @endif
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
            color: white;
            display: flex;
            flex-direction: column;

            /* dynamic sidebar background: green for agency, near-black for MCMC, blue for public */
            background-color:
                @if($r === 'agency_staff')   #047857 {{-- Tailwind‐green‐700 --}}
                @elseif($r === 'mcmc_staff') #1F1F1F {{-- near-black --}}
                @else                         #2563EB {{-- Tailwind‐blue‐700 --}}
                @endif
            ;
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
            /* Use a slightly different color for the left‐border “active” line */
            border-left-color: 
                @if($r === 'agency_staff')   #059669  {{-- green‐600 --}}
                @elseif($r === 'mcmc_staff') #888888  {{-- gray for MCMC --}}
                @else                         #d9534f  {{-- red for public --}}
                @endif
            ;
        }
        aside .sidebar-footer {
            padding: 20px;
        }
        aside .sidebar-footer form button {
            width: 100%;
            padding: 10px;
            background-color: #d9534f; /* logout button stays red */
            border: none;
            color: white;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.2s;
        }
        aside .sidebar-footer form button:hover {
            background-color: #c9302c;
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
        h2.section-title,
        h3.section-title {
            font-size: 2rem;

            /* Heading color, dynamic: green for agency, black for MCMC, blue for public */
            color:
                @if($r === 'agency_staff')   #059669 {{-- green‐600 --}}
                @elseif($r === 'mcmc_staff') #000000 {{-- black --}}
                @else                         #007ACC {{-- blue‐600 --}}
                @endif
            ;

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

            /* Avatar border dynamic: green for agency, black for MCMC, blue for public */
            border: 4px solid 
                @if($r === 'agency_staff')   #059669 {{-- green‐600 --}}
                @elseif($r === 'mcmc_staff') #000000 {{-- black --}}
                @else                         #007ACC {{-- blue‐600 --}}
                @endif
            ;

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

            /* Button background dynamic: green for agency, black for MCMC, blue for public */
            background-color:
                @if($r === 'agency_staff')   #059669 {{-- green‐600 --}}
                @elseif($r === 'mcmc_staff') #000000 {{-- black --}}
                @else                         #007ACC {{-- blue‐600 --}}
                @endif
            ;

            color: white;
            font-weight: 600;
            text-align: center;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .btn:hover {
            background-color:
                @if($r === 'agency_staff')   #34D399 {{-- green‐300 --}}
                @elseif($r === 'mcmc_staff') #333333 {{-- dark gray --}}
                @else                         #89CFF0 {{-- light blue --}}
                @endif
            ;
        }
    </style>

    {{-- Child views can still inject additional <style> or <script> if they want --}}
    @yield('head')
</head>
<body>
    <!-- HEADER -->
    <header>
        @php
            $user = Auth::user();
            $role = $user->role ?? null;
            $isFirstLogin = $user->is_first_login ?? false;
        @endphp

        @php
        $user = Auth::user();

        if ($user->role === 'agency_staff' && ! $user->is_first_login) {
        $homeRoute = 'dashboard.agency';
        } elseif ($user->role === 'mcmc_staff') {
        $homeRoute = 'dashboard.mcmc';
        } else {
        $homeRoute = 'dashboard.public';
        }
        @endphp

        <a href="{{ route($homeRoute) }}">
        <h1>Welcome to MySebenarnya</h1>
        </a>


        <div class="header-icons">
            <img src="https://cdn.jsdelivr.net/npm/feather-icons/dist/icons/bell.svg" alt="Notifications" />
        </div>
    </header>

    <!-- SIDEBAR -->
    <aside>
        <nav>
            <ul>


    
                {{-- Existing “Home” link --}}
                <li>
                    @inject('auth', 'Illuminate\Support\Facades\Auth')
                    @php
                         $user = $auth::user();

                        if ($user && $user->role === 'agency_staff' && ! $user->is_first_login) {
                            $homeRoute = 'dashboard.agency';
                        } elseif ($user && $user->role === 'mcmc_staff') {
                            $homeRoute = 'dashboard.mcmc';
                        } else {
                            $homeRoute = 'dashboard.public';
                        }
                    @endphp

                
                
                    <a 
                        href="{{ route($homeRoute) }}" 
                        class="{{ request()->routeIs($homeRoute) ? 'active' : '' }}"
                    >
                        Home
                    </a>
                </li>

                {{-- Only show these to MCMC staff --}}
@if($currentUser && $currentUser->role === 'mcmc_staff')
<li>
    <a href="{{ route('mcmc.create-account') }}"
       class="{{ request()->routeIs('mcmc.create-account') ? 'active' : '' }}">
        Create Account
    </a>
</li>
<li>
    <a href="{{ route('mcmc.inquiries') }}"
       class="{{ request()->routeIs('mcmc.inquiries*') ? 'active' : '' }}">
        Manage Inquiries
    </a>
</li>

<li>
    <a href="{{ route('mcmc.manage-process') }}"
       class="{{ request()->routeIs('mcmc.manage-process') ? 'active' : '' }}">
        Progress Report
    </a>
</li>



<li>
    <a href="{{ route('mcmc.users') }}"
       class="{{ request()->routeIs('mcmc.users') ? 'active' : '' }}">
        User Directory
    </a>
</li>
<li>
    <a href="{{ route('mcmc.assignment-report') }}"
       class="{{ request()->routeIs('mcmc.assignment-report') ? 'active' : '' }}">
        Assignment Report
    </a>
</li>
<li>
    <a href="{{ url('/mcmc/assign') }}"
       class="{{ request()->is('mcmc/assign') ? 'active' : '' }}">
        Assign Inquiry
    </a>
</li>
@endif

    
                {{-- Inquiry Menu based on user role --}}

@if($user->role === 'agency_staff' && ! $user->is_first_login)
    {{-- Agency Staff: Track Inquiries --}}
    <li>
        <a 
            href="{{ route('agency.inquiries') }}" 
            class="{{ request()->routeIs('agency.inquiries*') ? 'active' : '' }}"
        >
            Track Inquiries
        </a>
    </li>
@endif

{{-- “Profile” link (existing) --}}
<li>
    <a 
        href="{{ 
            ($user->role === 'agency_staff') 
                ? route('agency.profile') 
                : ( ($user->role === 'mcmc_staff') 
                    ? route('mcmc.profile') 
                    : route('profile') ) 
        }}"
        class="{{ request()->routeIs('*.profile') ? 'active' : '' }}"
    >
        Profile
    </a>
</li>

    
                {{-- “Settings” link (existing) 
                <li>
                    <a 
                        href="{{ route('settings') }}" 
                        class="{{ request()->routeIs('settings') ? 'active' : '' }}"
                    >
                        Notifications
                    
                    </a>
                </li>--}}


         {{-- Inquiry Menu based on user role --}}
@if($role === 'mcmc_staff')
    {{-- MCMC already handled above --}}
@elseif($role === 'agency_staff' && ! $isFirstLogin)
    {{-- Agency Staff: Track Inquiries 
    <li>
        <a 
            href="{{ route('agency.inquiries') }}" 
            class="{{ request()->routeIs('agency.inquiries*') ? 'active' : '' }}"
        >
            Track Inquiries
        </a>
    </li> --}}
@elseif($role === 'public_user')
    {{-- Public Users: Submit Inquiry --}}
    <li>
        <a 
            href="{{ route('inquiry.create') }}" 
            class="{{ request()->routeIs('inquiry.create') ? 'active' : '' }}"
        >
            Submit Inquiry
        </a>
    </li>

    {{-- Public Users: Inquiry History --}}
    <li>
        <a 
            href="{{ route('inquiry.history') }}" 
            class="{{ request()->routeIs('inquiry.history') ? 'active' : '' }}"
        >
            Inquiry History
        </a>
    </li>
@endif

{{-- Settings (only one time for all roles) --}}
<li>
    <a 
        href="{{ route('settings') }}" 
        class="{{ request()->routeIs('settings') ? 'active' : '' }}"
    >
        Notifications
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
