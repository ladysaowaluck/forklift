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
            --color-font: #004B8D;
            /* Main background color for all pages */
            --color-border: #e3e6f0;
            --border-radius-md: 0.75rem;
            --box-shadow-subtle: 0 2px 6px rgba(0, 0, 0, 0.04);

            --topbar-height: 60px;
            --sidebar-width: 250px;
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

        /* 
        .navbar {
            position: sticky;
            top: 0;
            z-index: 1050;
            --bs-navbar-padding-y: -0.5rem;
        } */

        /* 
        .navbar-dark .navbar-toggler {
            --bs-navbar-toggler-border-color: rgba(133, 133, 133, 0.6);
            --bs-navbar-toggler-focus-width: 0.2rem;
            border-radius: 0.5rem;
        } */

        .navbar-dark .navbar-toggler-icon {
            --bs-navbar-toggler-icon-bg: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23004B8D' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Top bar */
        .app-topbar {
            height: var(--topbar-height);
            padding-block: 0;
        }

        .navbar-logo {
            height: 38px;
            width: auto;
        }

        /* Sidebar / drawer */
        .app-sidebar {
            /* background-color: color-mix(in srgb, var(--color-primary) 85%, black); */
            --bs-offcanvas-bg: var(--color-font);
            display: flex;
            flex-direction: column;
        }

        .app-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            padding: 0.65rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease-in-out;
        }

        .app-sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .app-sidebar .nav-link.active {
            color: #fff;
            font-weight: 700;
            background-color: rgba(255, 255, 255, 0.2);
        }

        .offcanvas-lg {
            background-color: #004B8D;
        }

        /* DESKTOP (>= 992px): fixed sidebar under the top bar, content pushed right */
        @media (min-width: 992px) {
            .app-sidebar {
                position: fixed;
                top: var(--topbar-height);
                bottom: 0;
                left: 0;
                width: var(--sidebar-width);
                overflow-y: auto;
                background-color: color-mix(in srgb, var(--color-font) 85%, black) !important;
            }

            .app-content {
                margin-left: var(--sidebar-width);
            }
        }

        /* MOBILE (< 992px): drawer width */
        @media (max-width: 991.98px) {
            .app-sidebar {
                --bs-offcanvas-width: 280px;
            }
        }

        .sidebar-body {
            display: flex;
            flex-direction: column;
            flex-grow: 1 !important;
            overflow-y: auto;
        }

        .sidebar-credit {
            color: rgba(255, 255, 255, 0.55);
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            letter-spacing: 0.02em;
        }

        /* .user-account {
            color: #004B8D;
            padding: 0.65rem 1rem;
            background-color: color-mix(in srgb, var(--color-font) 85%, black) !important;
        } */

        .user-toggle {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 0.75rem;
            color: #004B8D;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 50rem;
            transition: background-color 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .user-toggle:hover,
        .user-toggle:focus-visible,
        .user-toggle.show {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.7);
        }

        .user-toggle:focus-visible {
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25);
        }

        .user-account {
            color: #004B8D;
        }

        /* Truncate long names */
        .user-name {
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Dropdown menu */
        .user-menu {
            min-width: 200px;
            margin-top: 0.5rem !important;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 0.75rem;
            overflow: hidden;
            padding: 0;
        }

        .user-menu-header {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .user-menu .dropdown-item {
            padding: 0.65rem 1rem;
            transition: background-color 0.15s ease, padding-left 0.15s ease;
        }

        .user-menu .dropdown-item:hover,
        .user-menu .dropdown-item:focus {
            background-color: rgba(255, 255, 255, 0.12);
            padding-left: 1.25rem;
            /* small slide effect on hover */
        }

        /* Phones: icon-only round button, bigger tap target */
        @media (max-width: 575.98px) {
            .user-toggle {
                padding: 0.4rem 0.6rem;
            }

            .user-toggle.dropdown-toggle::after {
                display: none;
                /* hide the caret to save space */
            }
        }

        /* Larger screens: allow longer names */
        @media (min-width: 992px) {
            .user-name {
                max-width: 200px;
            }
        }

        .fa-lg {
            font-size: 1.75em;
        }
    </style>
</head>

<body>
    <div id="app">

        <!-- TOP BAR: always visible (desktop + mobile) -->
        <nav class="navbar navbar-dark sticky-top shadow-sm app-topbar" style="background-color: var(--color-primary);">
            <div class="container-fluid px-3 px-lg-4">

                <div class="d-flex align-items-center">
                    <!-- Only render mobile navigation toggler for Admin users -->
                    @auth
                        @if(Auth::user()->isAdmin())
                            <!-- Toggler: visible on mobile only -->
                            <button class="navbar-toggler d-lg-none me-2" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#sidebarMenu" aria-controls="sidebarMenu"
                                aria-label="{{ __('Toggle navigation') }}">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                        @endif
                    @endauth

                    <a class="navbar-brand fw-bolder fs-4 d-flex align-items-center m-0"
                        href="{{ url('/transactions') }}">
                        <img src="{{ asset('images/Logo_Nissin.png') }}" alt="Siam Nistran Logo"
                            class="navbar-logo me-2">
                        <span class="d-none d-sm-inline">{{ config('app.name', 'Forklift Tracker') }}</span>
                    </a>
                </div>

                @auth
                    <!-- User dropdown (top right, desktop + mobile) -->
                    <div class="dropdown" data-bs-theme="dark">
                        <a id="navbarDropdown"
                            class="user-account text-decoration-none dropdown-toggle d-flex align-items-center" href="#"
                            role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <i class="fas fa-user-circle fa-lg me-2"></i>
                            <span class="d-none d-sm-inline">{{ Auth::user()->name }}</span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end user-menu shadow" aria-labelledby="navbarDropdown">
                            <!-- <a class="dropdown-item" href="{{ route('logout') }}"
                                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                        <i class="fas fa-sign-out-alt me-2"></i>{{ __('Logout') }}
                                                    </a> -->
                            <div class="user-menu-header px-3 py-2">
                                <div class="fw-semibold text-truncate">{{ Auth::user()->name }}</div>
                                <div class="small text-body-secondary text-capitalize">{{ Auth::user()->role }}</div>
                            </div>
                            <hr class="dropdown-divider">

                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt fa-fw me-2"></i>{{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </nav>

        <!-- MENU: sidebar on desktop, slide-in drawer on mobile (Admin only) -->
        <!-- Display sidebar navigation exclusively for Admin users -->
        @auth
            @if(Auth::user()->isAdmin())
                <div class="offcanvas-lg offcanvas-start app-sidebar" tabindex="-1" id="sidebarMenu" data-bs-theme="dark">

                    <div class="offcanvas-header">
                        <!-- <img src="{{ asset('images/SNC_logo_white.png') }}" alt="Company Logo" class="login-logo mb-3 mb-md-4"> -->
                        <h5 class="offcanvas-title">{{ config('app.name', 'Forklift Tracker') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu"
                            aria-label="Close"></button>
                    </div>

                    <div class="offcanvas-body p-3">
                        <ul class="nav nav-pills flex-column gap-1 w-100">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('transactions*') ? 'active' : '' }}"
                                    href="{{ route('transactions.index') }}">
                                    <i class="fas fa-calendar-check fa-fw me-2"></i>Bookings
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('warehouses*') || request()->is('warehouse-management*') ? 'active' : '' }}"
                                    href="{{ route('warehouse.management') }}">
                                    <i class="fas fa-warehouse fa-fw me-2"></i>{{ __('Warehouses') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('forklifts*') || request()->is('forklift-management*') ? 'active' : '' }}"
                                    href="{{ route('forklift.management') }}">
                                    <i class="fas fa-truck-moving fa-fw me-2"></i>{{ __('Forklifts') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('user-management*') ? 'active' : '' }}"
                                    href="{{ route('user.management') }}">
                                    <i class="fas fa-users fa-fw me-2"></i>{{ __('Users') }}
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="sidebar-credit mt-auto pt-3 text-center small">
                        &copy; {{ date('Y') }} updated Nissin HB
                    </div>

                </div>
            @endif
        @endauth

        <!-- PAGE CONTENT -->
        <main class="{{ Auth::check() && Auth::user()->isAdmin() ? 'app-content' : '' }}">
            @yield('content')
        </main>
    </div>


</body>

</html>