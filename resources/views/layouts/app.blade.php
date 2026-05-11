<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'MySebenarnya')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">

    <!-- TOP NAVIGATION -->
    @php
    $currentRoute = request()->path();
    $isInquiryPage = str_starts_with($currentRoute, 'inquiry') || $currentRoute === 'thank-you';

    $role = Auth::check() ? Auth::user()->role : null;
    $headerClass = match(true) {
        $isInquiryPage             => 'bg-blue-600 text-white',
        $role === 'agency_staff'   => 'bg-green-600 text-white',
        $role === 'mcmc_staff'     => 'bg-black text-white',
        default                    => 'bg-white text-gray-800'
    };
@endphp

<nav class="{{ $headerClass }} shadow">


        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-2xl font-bold {{ $headerClass === 'bg-white text-gray-800' ? 'text-gray-800' : 'text-white' }}">
    MySebenarnya
            </a>


            <div>
                @guest
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-800 mr-6">Login</a>
                    <a href="{{ route('register') }}" class="text-gray-600 hover:text-gray-800">Register</a>
                @else
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-white hover:text-gray-200 font-medium">Logout</button>
                    </form>
                @endguest
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="container mx-auto px-4">
        @yield('content')
    </main>

</body>
</html>
