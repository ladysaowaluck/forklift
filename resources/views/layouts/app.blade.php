
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Forklift Tracker') }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        :root {
            --color-primary: #0054a6;
            /* Tabler Blue */
            --color-secondary: #6c7a9c;
            /* Tabler Slate Muted */
            --color-success: #2fb344;
            /* Tabler Green */
            --color-info: #4299e1;
            /* Tabler Azure */
            --color-warning: #f76707;
            /* Tabler Orange */
            --color-danger: #d63939;
            /* Tabler Red */
            --color-inactive: #94a3b8;
            /* Tabler Gray */
            --color-background: #f6f8fb;
            /* Tabler Cool Off-White */
            --color-font: #182433;
            /* Tabler Dark Slate */
            --color-border: #e6e7e9;
            /* Tabler 1px Subtle Border */
            --color-sidebar: #182433;
            /* Tabler Classic Dark Navy */
            --border-radius-md: 0.925rem;
            --box-shadow-subtle: 0 1px 3px 0 rgba(0, 0, 0, 0.04), 0 1px 2px -1px rgba(0, 0, 0, 0.04);
            --box-shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.07), 0 4px 6px -4px rgba(0, 0, 0, 0.03);

            --topbar-height: 60px;
            --sidebar-width: 250px;
        }
        html,
        body {
            font-size: 15px;
            -webkit-text-size-adjust: 100%;
        }

        body {
            background-color: var(--color-background);
            color: var(--color-font);
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            font-size: 15px;
            min-width: 320px;
            margin: 0 auto;
            letter-spacing: -0.01em;
        }

        @media (max-width: 767.98px) {
            body {
                font-size: 15px !important;
            }

            .modern-table,
            .table {
                font-size: 0.875rem !important;
            }
        }

        .btn-action-primary {
            background-color: #0054a6 !important;
            border-color: #0054a6 !important;
            color: #ffffff !important;
            font-weight: 600;
            transition: all 0.15s ease-in-out;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .btn-action-primary:hover,
        .btn-action-primary:focus {
            background-color: #003d7a !important;
            border-color: #003870 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 6px -1px rgba(0, 84, 166, 0.3);
            transform: translateY(-1px);
        }

        .btn-action-primary:active {
            background-color: #003366 !important;
            border-color: #003366 !important;
            transform: translateY(0);
            box-shadow: none;
        }

        .card-main-content {
            background-color: #ffffff;
            border: 1px solid var(--color-border);
            box-shadow: var(--box-shadow-subtle);
            border-radius: var(--border-radius-md);
        }

        .modern-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
            letter-spacing: -0.01em;
        }

        .modern-table thead th {
            position: sticky;
            top: 0;
            z-index: 3;
            background-color: #f8fafc;
            color: #64748b;
            font-weight: 700;
            font-size: 0.725rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 0.55rem 0.75rem;
            border-top: 0;
            border-bottom: 1px solid var(--color-border);
            box-shadow: inset 0 -1px 0 var(--color-border);
            white-space: nowrap;
        }

        .modern-table tbody tr {
            background-color: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            transition: background-color 0.15s ease-in-out;
        }

        .modern-table tbody tr:hover,
        .modern-table tbody tr.clickable-row:hover,
        .modern-table tbody tr.transaction-row:hover {
            background-color: #f8fafc;
        }

        .modern-table tbody td {
            padding: 0.55rem 0.75rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.875rem;
            color: #1e293b;
        }

        /* High-density numeric alignment for quick scanning */
        .modern-table th.col-num,
        .modern-table td.col-num,
        .modern-table .text-end {
            text-align: right !important;
            font-variant-numeric: tabular-nums;
        }

        /* Form input check column density */
        .modern-table th.col-check,
        .modern-table td.col-check {
            width: 38px;
            text-align: center;
            padding-left: 0.65rem;
            padding-right: 0.35rem;
        }

        .modern-table .form-check-input {
            cursor: cell;
            width: 1.05rem;
            height: 1.05rem;
            border-color: #cbd5e1;
            border-radius: 0.25em;
            /* Forces sharp square corners */
            -webkit-appearance: none;
            /* Prevents default browser rendering overrides */
            appearance: none;
        }

        .modern-table .form-check-input:checked {
            background-color: #0054a6;
            border-color: #0054a6;
            border-radius: 0.25em;
        }

        .status-badge,
        .role-badge {
            padding: 0.28em 0.75em;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 50rem;
            color: #fff;
            letter-spacing: 0.02em;
            display: inline-block;
        }

        /* Topbar height and logo */
        .app-topbar {
            height: var(--topbar-height);
            padding-block: 0;
            background-color: #ffffff !important;
            border-bottom: 1px solid var(--color-border) !important;
        }

        .navbar-logo {
            height: 38px;
            width: auto;
        }

        /* Modern User Profile Pill */
        .user-avatar-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.75rem;
            border-radius: 50rem;
            background: #f1f5f9;
            border: 1px solid var(--color-border);
            color: var(--color-font);
            text-decoration: none;
            transition: all 0.15s ease-in-out;
        }

        .user-avatar-pill:hover,
        .user-avatar-pill:focus {
            background: #e2e8f0;
            color: var(--color-primary);
        }

        .user-menu-dropdown {
            min-width: 200px;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.04);
            border: 1px solid var(--color-border);
            overflow: hidden;
        }

        .app-sidebar {
            --bs-offcanvas-bg: #ffffff;
            background-color: #ffffff !important;
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--color-border);
        }

        .app-sidebar .nav-link {
            color: #4b5563;
            font-weight: 500;
            font-size: 0.875rem;
            padding: 0.55rem 0.85rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            transition: all 0.15s ease-in-out;
        }

        .app-sidebar .nav-link:hover {
            color: #111827;
            background-color: #f1f5f9;
        }

        .app-sidebar .nav-link.active {
            color: #0054a6;
            font-weight: 600;
            background-color: #e8f1fa;
        }

        /* Gemini : Increase sidebar icon size and set vertical alignment for better visual hierarchy and readability */
        .app-sidebar .nav-link i {
            font-size: 1.35rem;
            line-height: 1;
            vertical-align: middle;
            color: #64748b;
            transition: color 0.15s ease-in-out;
        }

        .app-sidebar .nav-link:hover i,
        .app-sidebar .nav-link.active i {
            color: #0054a6;
        }

        .offcanvas-lg {
            background-color: #ffffff;
        }

        /* DESKTOP (>= 992px): Fixed minimal sidebar below topbar */
        @media (min-width: 992px) {
            .app-sidebar {
                position: fixed;
                top: var(--topbar-height);
                bottom: 0;
                left: 0;
                width: var(--sidebar-width);
                overflow-y: auto;
                background-color: #ffffff !important;
                border-right: 1px solid var(--color-border);
                z-index: 1020;
            }

            .app-content {
                margin-left: var(--sidebar-width);
            }
        }

        /* MOBILE (< 992px): Drawer width */
        @media (max-width: 991.98px) {
            .app-sidebar {
                --bs-offcanvas-width: 280px;
            }
        }

        .sidebar-credit {
            color: #94a3b8;
            border-top: 1px solid var(--color-border);
            letter-spacing: 0.02em;
            padding: 0.85rem 0;
        }

        .app-container {
            min-width: 320px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="app-container">
        <div id="app">

            <!-- TOP BAR: Single modern navbar retained from this version -->
            <nav class="navbar navbar-expand-md sticky-top bg-white border-bottom shadow-sm app-topbar">
                <div class="container-fluid px-3 px-lg-4">

                    <div class="d-flex align-items-center">
                        @auth
                            @if(in_array(strtolower(Auth::user()->role ?? ''), ['admin', 'checker']))
                                <!-- Toggler: visible on mobile only, opens classic sidebar drawer -->
                                <button class="navbar-toggler d-lg-none me-2 border-0 p-1" type="button"
                                    data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu"
                                    aria-label="{{ __('Toggle navigation') }}">
                                    <i class="ti ti-menu-2 fs-4 text-dark"></i>
                                </button>
                            @endif
                        @endauth

                        <a class="navbar-brand fw-bolder fs-4 d-flex align-items-center m-0 text-dark text-decoration-none"
                            href="{{ url('/transactions') }}">
                            <img src="{{ asset('images/ForkLifting.jpg') }}" alt="Logo" class="navbar-logo me-2">
                            <span class="d-none d-sm-inline">{{ config('app.name', 'Forklift Tracker') }}</span>
                        </a>
                    </div>

                    <!-- Right-side User Menu -->
                    <div class="navbar-nav flex-row ms-auto align-items-center">
                        @auth
                            <div class="nav-item dropdown">
                                <a id="navbarDropdown" class="user-avatar-pill dropdown-toggle text-decoration-none"
                                    href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span
                                        class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center me-2"
                                        style="width: 28px; height: 28px; font-size: 0.85rem;">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <div class="d-none d-sm-block text-start lh-1 me-1">
                                        <div class="fw-semibold text-dark text-truncate" style="max-width: 140px;">
                                            {{ Auth::user()->name }}
                                        </div>
                                        <div class="text-muted text-capitalize" style="font-size: 0.725rem;">
                                            {{ Auth::user()->role }}
                                        </div>
                                    </div>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end user-menu-dropdown shadow mt-2 p-0"
                                    aria-labelledby="navbarDropdown">
                                    <div class="px-3 py-2 bg-light border-bottom">
                                        <div class="fw-bold text-dark text-truncate">{{ Auth::user()->name }}</div>
                                        <div class="small text-muted text-capitalize">{{ Auth::user()->role }}</div>
                                    </div>

                                    <a class="dropdown-item text-danger py-2 px-3" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="ti ti-logout me-2"></i>{{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Login</a>
                        @endauth
                    </div>
                </div>
            </nav>

            @auth
                @if(in_array(strtolower(Auth::user()->role ?? ''), ['admin', 'checker']))
                    <!-- Minimal-clean sidebar container and offcanvas header -->
                    <div class="offcanvas-lg offcanvas-start app-sidebar shadow-sm" tabindex="-1" id="sidebarMenu">

                        <div class="offcanvas-header border-bottom py-3">
                            <h5 class="offcanvas-title text-dark fw-bold d-flex align-items-center mb-0">
                                <img src="{{ asset('images/ForkLifting.jpg') }}" alt="Logo" class="rounded me-2"
                                    style="max-height: 28px;">
                                <span class="fs-6">{{ config('app.name', 'Forklift Tracker') }}</span>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu"
                                aria-label="Close"></button>
                        </div>

                        <div class="offcanvas-body p-3">
                            <ul class="nav nav-pills flex-column gap-1 w-100">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}"
                                        href="{{ route('dashboard') }}">
                                        <i class="ti ti-layout-dashboard me-2"></i>Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('transactions*') ? 'active' : '' }}"
                                        href="{{ route('transactions.index') }}">
                                        <i class="ti ti-calendar-event me-2"></i>Bookings
                                    </a>
                                </li>

                                {{-- Admin-only management links --}}
                                @if(Auth::user()->isAdmin())
                                    <li class="nav-item">
                                        <a class="nav-link {{ (request()->is('warehouse*') || request()->is('warehouses*') || request()->is('warehouse-management*')) && !request()->is('warehouse-routes*') ? 'active' : '' }}"
                                            href="{{ route('warehouse.management') }}">
                                            <i class="ti ti-building-warehouse me-2"></i>{{ __('Warehouses') }}
                                        </a>
                                    </li>
                                    <!-- Sub-button under Warehouses for Routes & Distance management -->
                                    <li class="nav-item ms-3">
                                        <a class="nav-link py-1 {{ request()->is('warehouse-routes*') ? 'active text-primary fw-bold' : 'text-muted' }}"
                                            href="{{ route('warehouse.routes.index') }}" style="font-size: 0.82rem;">
                                            <i class="ti ti-route me-1"></i>{{ __('Routes & Distance') }}
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->is('forklift*') || request()->is('forklifts*') || request()->is('forklift-management*') ? 'active' : '' }}"
                                            href="{{ route('forklift.management') }}">
                                            <i class="ti ti-forklift me-2"></i>{{ __('Forklifts') }}
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->is('user-management*') ? 'active' : '' }}"
                                            href="{{ route('user.management') }}">
                                            <i class="ti ti-users me-2"></i>{{ __('Users') }}
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <div class="sidebar-credit mt-auto pt-3 text-center small">
                            &copy; {{ date('Y') }} updated Nissin HB
                        </div>

                    </div>
                @endif
            @endauth

            <main
                class="{{ Auth::check() && in_array(strtolower(Auth::user()->role ?? ''), ['admin', 'checker']) ? 'app-content' : '' }}">
                @yield('content')
            </main>
        </div>
    </div>

</body>

</html>