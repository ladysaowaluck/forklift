@extends('layouts.app')

@section('content')
{{-- Ensure FontAwesome is loaded for icons --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
    :root {
        --color-primary: #E60012;      
        --color-secondary: #004B8D;     
        --color-info: #0086BF;         
        --color-inactive: #6c757d;     
        --color-background: #f4f6f9;
        --color-border: #e3e6f0;
        --border-radius-md: 0.75rem;
        --box-shadow-subtle: 0 2px 6px rgba(0, 0, 0, 0.04);
    }
    body {
        background-color: var(--color-background);
        color: #212529;
    }
    .card-main-content {
        background-color: #ffffff;
        border: 1px solid var(--color-border);
        box-shadow: 0 4px 18px rgba(0,0,0,0.05);
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
    .modern-table tbody tr:hover {
        transform: scale(1.01);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
        z-index: 10;
        position: relative;
    }
    .modern-table tbody td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
        border: 0;
    }
    .modern-table tbody td:first-child { border-top-left-radius: var(--border-radius-md); border-bottom-left-radius: var(--border-radius-md); }
    .modern-table tbody td:last-child { border-top-right-radius: var(--border-radius-md); border-bottom-right-radius: var(--border-radius-md); }
    .role-badge {
        padding: 0.4em 1em;
        font-size: 0.85rem;
        font-weight: 700;
        border-radius: 50rem;
        color: #fff;
    }
</style>

<div class="container-fluid py-4 px-lg-4">
    
    {{-- Main Header --}}
    <div class="text-center mb-4">
        <h1 class="display-5 fw-bolder" style="color: var(--color-primary);">
            <i class="fas fa-users-cog"></i> User Management
        </h1>
        <p class="text-secondary fs-5 fw-medium">Manage all users and their roles</p>
    </div>

    {{-- Session Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Main Content Card for Table --}}
    <div class="card card-main-content">
        <div class="card-body p-4 p-lg-5">
            
            {{-- Search and Add User Bar --}}
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
                <form action="{{ route('user.management') }}" method="GET" class="d-flex w-100 w-md-50 mb-3 mb-md-0">
                    <input type="text" name="search" class="form-control" placeholder="Search by name or email" value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-secondary ms-2">Search</button>
                </form>

            </div>
                <a href="{{ route('user.create') }}" class="btn text-white" style="background-color: var(--color-primary);">
                    <i class="fas fa-user-plus me-2"></i>Add New User
                </a>
               <!-- <a href="{{ route('user.create') }}" class="btn text-white" style="background-color: var(--color-primary);">
                    <i class="fas fa-user-plus me-2"></i>Add New User
                </a>-->
            </div>

            <div class="table-responsive">
                <table class="table modern-table align-middle">
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
                            <tr>
                                <td class="text-start fw-bold">{{ $user->name }}</td>
                                <td class="text-start text-muted">{{ $user->email }}</td>
                                <td>
                                    @php
                                        $roleColor = match(strtolower($user->role)) {
                                            'admin' => 'var(--color-primary)',
                                            'checker' => 'var(--color-secondary)',
                                            'driver' => 'var(--color-info)',
                                            default => 'var(--color-inactive)',
                                        };
                                    @endphp
                                    <span class="role-badge" style="background-color: {{ $roleColor }};">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>{{ $user->warehouse->name ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('user.edit', $user->id) }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center p-5">
                                    <p class="mb-0 text-muted">No users found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            @if ($users->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{-- FIXED: Using Bootstrap 5 for pagination styling --}}
                    {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection