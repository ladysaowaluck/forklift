@extends('layouts.app')

@section('content')
    {{-- Ensure FontAwesome is loaded for icons --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --color-primary: #ffffff;
            --color-secondary: #004B8D;
            --color-info: #0086BF;
            --color-inactive: #6c757d;
            --color-background: #f4f6f9;
            --color-border: #e3e6f0;
            --color-admin: #FA7800;
            --border-radius-md: 0.75rem;
            --box-shadow-subtle: 0 2px 6px rgba(0, 0, 0, 0.04);
        }

        body {
            background-color: var(--color-background);
            color: #212529;
            font-size: clamp(0.875rem, 1.5vw, 1rem);
        }

        .card-main-content {
            background-color: #ffffff;
            border: 1px solid var(--color-border);
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.05);
            border-radius: var(--border-radius-md);
        }

        .modern-table {
            border-collapse: separate;
            border-spacing: 0 0.5rem;
            font-size: 0.875rem;
            white-space: nowrap;
        }

        @media (min-width: 768px) {
            .modern-table {
                border-spacing: 0 0.75rem;
                font-size: 1rem;
            }
        }

        .modern-table thead th {
            border: 0;
            color: #6c757d;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            padding-top: 0;
        }

        @media (min-width: 768px) {
            .modern-table thead th {
                font-size: 0.85rem;
            }
        }

        .modern-table tbody tr {
            background-color: #fff;
            border-radius: var(--border-radius-md);
            box-shadow: var(--box-shadow-subtle);
            border: 1px solid var(--color-border);
            transition: all 0.2s ease-out;
        }

        .modern-table tbody tr:hover {
            transform: scale(1.01);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            z-index: 10;
            position: relative;
        }

        .modern-table tbody td {
            padding: 0.75rem 0.75rem;
            vertical-align: middle;
            border: 0;
        }

        @media (min-width: 768px) {
            .modern-table tbody td {
                padding: 1rem 1.5rem;
            }
        }

        .modern-table tbody td:first-child {
            border-top-left-radius: var(--border-radius-md);
            border-bottom-left-radius: var(--border-radius-md);
        }

        .modern-table tbody td:last-child {
            border-top-right-radius: var(--border-radius-md);
            border-bottom-right-radius: var(--border-radius-md);
        }

        .role-badge {
            padding: 0.3em 0.8em;
            font-size: 0.75rem;
            font-weight: 700;
            border-radius: 50rem;
            color: #fff;
            display: inline-block;
        }

        @media (min-width: 768px) {
            .role-badge {
                padding: 0.4em 1em;
                font-size: 0.85rem;
            }
        }
    </style>

    <div class="container-fluid py-3 py-md-4 px-2 px-md-4">

        {{-- Main Header --}}
        <div class="text-center mb-3 mb-md-4">
            <h1 class="fw-bolder fs-3 fs-md-1" style="color: var(--color-secondary);">
                <i class="fas fa-users-cog"></i> User Management
            </h1>
            <p class="text-secondary fs-6 fs-md-5 fw-medium mb-0">Manage all users and their roles</p>
        </div>

        {{-- Session Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Main Content Card for Table --}}
        <div class="card card-main-content border-0">
            <div class="card-body p-3 p-sm-4 p-lg-5">


                <div
                    class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center mb-3 mb-md-4 gap-2">
                    <form action="{{ route('user.management') }}" method="GET" class="d-flex w-100 w-md-50">
                        <input type="text" name="search" class="form-control form-control-sm form-control-md-normal me-2"
                            placeholder="Search by name or email" value="{{ request('search') }}">
                        <button type="submit" class="btn btn-outline-secondary btn-sm btn-md-normal">Search</button>
                    </form>

                    <a href="{{ route('user.create') }}"
                        class="btn btn-primary btn-sm btn-md-normal text-white px-3 fw-bold w-md-auto"
                        style="background-color: var(--color-secondary); border-color: var(--color-primary);">
                        <i class="fas fa-user-plus me-2"></i>Add New User
                    </a>
                </div>

            </div>

            

            <div class="user-management-container">
                 <!-- DESKTOP VIEW (Table Layout) -->
                    <div class="table-responsive d-none d-md-block">
                        <table class="table modern-table align-middle mb-0">
                            <thead class="text-center">
                                <tr>
                                    <th class="text-start">Name</th>
                                    <th class="text-start">Email</th>
                                    <th>Role</th>
                                    <th>Warehouse</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @forelse ($users as $user)
                                    @php
                                        $roleColor = match (strtolower($user->role)) {
                                            'admin' => 'var(--color-admin)',
                                            'checker' => 'var(--color-secondary)',
                                            'driver' => 'var(--color-info)',
                                            default => 'var(--color-inactive)',
                                        };
                                    @endphp
                                    <tr>
                                        <td class="text-start fw-bold">{{ $user->name }}</td>
                                        <td class="text-start text-muted">{{ $user->email }}</td>
                                        <td>
                                            <span class="role-badge" style="background-color: {{ $roleColor }};">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td>{{ $user->warehouse->name ?? '-' }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{ route('user.edit', $user->id) }}"
                                                    class="btn btn-outline-secondary btn-sm">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </a>
                                                <form action="{{ route('user.destroy', $user->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                                        <i class="fas fa-trash-alt me-1"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center p-4">
                                            <p class="mb-0 text-muted">No users found.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                     <!-- MOBILE VIEW (Card Layout)  -->
                    <div class="d-block d-md-none">
                        @forelse ($users as $user)
                            @php
                                $roleColor = match (strtolower($user->role)) {
                                    'admin' => 'var(--color-admin)',
                                    'checker' => 'var(--color-secondary)',
                                    'driver' => 'var(--color-info)',
                                    default => 'var(--color-inactive)',
                                };
                            @endphp
                            <div class="card mb-2 border rounded-3 shadow-sm">
                                <div class="card-body p-3">
                                    <!-- User Header & Role Badge -->
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <div>
                                            <div class="fw-bold text-dark fs-6">{{ $user->name }}</div>
                                            <div class="small text-muted" style="font-size: 0.8rem;">{{ $user->email }}</div>
                                        </div>
                                        <span class="role-badge small"
                                            style="background-color: {{ $roleColor }}; font-size: 0.75rem; padding: 0.2rem 0.5rem;">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </div>

                                    <!-- Warehouse Info -->
                                    <div class="small text-muted mb-2 mt-2">
                                        <i class="fas fa-warehouse me-1"></i>
                                        <span>Warehouse: {{ $user->warehouse->name ?? 'Unassigned' }}</span>
                                    </div>

                                    <!-- Actions Row -->
                                    <div class="d-flex justify-content-end gap-2 mt-2 pt-2 border-top">
                                        <a href="{{ route('user.edit', $user->id) }}"
                                            class="btn btn-outline-secondary btn-sm py-1 px-2" style="font-size: 0.8rem;">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm py-1 px-2"
                                                style="font-size: 0.8rem;">
                                                <i class="fas fa-trash-alt me-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center p-3 border rounded bg-light">
                                <p class="mb-0 small text-muted">No users found.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Pagination --}}
                @if ($users->hasPages())
                    <div class="d-flex justify-content-center mt-3 mt-md-4">
                        {{-- FIXED: Using Bootstrap 5 for pagination styling --}}
                        {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection