<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Forklift Tracker') }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    {{-- Font Awesome for Icons (Should be here for all pages) --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        :root {
            --color-primary: #f3f3f3;
            /* Murata Primary Red */
            --color-secondary: #8B0019;
            /* Murata Secondary Blue */
            --color-success: #198754;
            --color-info: #0070C0;
            --color-warning: #B90019;
            --color-inactive: #6c757d;
            --color-background: #f4f6f9;
            /* Main background color for all pages */
            --color-border: #e3e6f0;
            --border-radius-md: 0.75rem;
            --box-shadow-subtle: 0 2px 6px rgba(0, 0, 0, 0.04);
        }

        body {
            background-color: var(--color-background);
            color: #212529;
            font-size: 16px;
        }

        .card-main-content {
            background-color: #ffffff;
            border: 1px solid var(--color-border);
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.05);
            border-radius: var(--border-radius-md);
        }

        .modern-table {
            border-collapse: separate;
            border-spacing: 0 0.75rem;
            font-size: 1rem;
        }

        .modern-table thead th {
            border: 0;
            color: #6c757d;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            padding-top: 0;
        }

        .modern-table tbody tr {
            background-color: #fff;
            border-radius: var(--border-radius-md);
            box-shadow: var(--box-shadow-subtle);
            border: 1px solid var(--color-border);
            transition: all 0.2s ease-out;
        }

        .modern-table tbody tr:hover,
        .modern-table tbody tr.clickable-row:hover {
            transform: scale(1.01);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            z-index: 10;
            position: relative;
            cursor: pointer;
            border-color: var(--color-primary);
        }

        .modern-table tbody td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
            border: 0;
        }

        .modern-table tbody td:first-child {
            border-top-left-radius: var(--border-radius-md);
            border-bottom-left-radius: var(--border-radius-md);
        }

        .modern-table tbody td:last-child {
            border-top-right-radius: var(--border-radius-md);
            border-bottom-right-radius: var(--border-radius-md);
        }

        .status-badge,
        .role-badge {
            padding: 0.4em 1em;
            font-size: 0.85rem;
            font-weight: 700;
            border-radius: 50rem;
            color: #fff;
        }

        /* NEW: Custom Navbar Link Styling for Clarity */
        .navbar-dark .nav-link {
            color: #004B8D;
            font-weight: 500;
            /* Medium weight for better readability */
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease-in-out;
        }

        .navbar-dark .nav-link:hover {
            color: #004B8D;
            background-color: rgba(0, 0, 0, 0.1);
            /* Subtle background highlight on hover */
        }

        /* Style for the currently active link */
        .navbar-dark .nav-link.active {
            color: #004B8D;
            font-weight: 700;
            /* Bolder for active link */
            background-color: rgba(0, 0, 0, 0.2);
            /* Darker background for active link */
        }

        .navbar-logo {
            height: 40px;
            width: auto;
        }

        .navbar {
            position: sticky;
            top: 0;
            z-index: 1050;
            /* Ensure it stays above other content  */
            --bs-navbar-padding-y: -0.5rem;
        }

        /* 
        .navbar-dark .navbar-toggler {
            --bs-navbar-toggler-border-color: rgba(133, 133, 133, 0.6);
            --bs-navbar-toggler-focus-width: 0.2rem;
            border-radius: 0.5rem;
        } */

        .navbar-dark .navbar-toggler-icon {
            --bs-navbar-toggler-icon-bg: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23004B8D' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
    </style>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark shadow-sm " style="background-color: var(--color-primary);">
            <div class="container-fluid px-lg-4">
                <a class="navbar-brand fw-bolder fs-4" href="{{ url('/transactions') }}">
                    <!-- <i class="fas fa-truck-moving"></i> -->
                    <img src="{{ asset('images/Logo_Nissin.png') }}" alt="Siam Nistran Logo" class="navbar-logo me-2">
                    {{ config('app.name', 'Forklift Tracker') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>




                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto">
                        @guest
                            {{-- Guest links for login/register --}}
                        @else
                            {{-- Admin-only Menu Items --}}
                            @if(Auth::user()->role == 'admin')
                                <li class="nav-item">
                                    {{-- ADDED: Logic to show 'active' class --}}
                                    <a class="nav-link {{ request()->is('transactions*') ? 'active' : '' }}"
                                        href="{{ route('transactions.index') }}">Bookings</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('warehouses*') || request()->is('warehouse-management*') ? 'active' : '' }}"
                                        href="{{ route('warehouse.management') }}">{{ __('Warehouses') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('forklifts*') || request()->is('forklift-management*') ? 'active' : '' }}"
                                        href="{{ route('forklift.management') }}">{{ __('Forklifts') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('user-management*') ? 'active' : '' }}"
                                        href="{{ route('user.management') }}">{{ __('Users') }}</a>
                                </li>
                            @endif

                            {{-- User Dropdown --}}
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fas fa-user-circle me-1"></i>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>
    </div>
</body>

</html>