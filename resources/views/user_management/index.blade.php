@extends('layouts.app')

@section('content')
    {{-- Lady : Removed redundant FontAwesome CDN link; Tabler icons are provided globally by layouts.app --}}

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
            font-size: 15px;
        }

        .card-main-content {
            background-color: #ffffff;
            border: 1px solid var(--color-border);
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.05);
            border-radius: var(--border-radius-md);
        }

        .modern-table {
            border-collapse: collapse;
            font-size: 0.875rem;
            width: 100%;
        }

        .modern-table thead th {
            position: sticky;
            top: 0;
            background: #f8fafc;
            color: #475569;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.55rem 0.75rem;
            border-bottom: 1px solid #cbd5e1;
            box-shadow: inset 0 -1px 0 #cbd5e1;
            z-index: 2;
            white-space: nowrap;
        }

        .modern-table tbody tr {
            background-color: #fff;
            border-bottom: 1px solid #f1f5f9;
            transition: background-color 0.12s ease;
        }

        .modern-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .modern-table tbody td {
            padding: 0.55rem 0.75rem;
            vertical-align: middle;
            border-top: none;
            border-bottom: 1px solid #f1f5f9;
        }

        .modern-table .col-num {
            font-variant-numeric: tabular-nums;
        }

        .role-badge {
            padding: 0.3em 0.8em;
            font-size: 0.75rem;
            font-weight: 700;
            border-radius: 50rem;
            color: #fff;
            display: inline-block;
        }

        .table-responsive {
            overscroll-behavior-x: contain;
            -webkit-overflow-scrolling: touch;
            scrollbar-gutter: stable;
        }

    </style>

    <div class="container-fluid py-3 py-md-4 px-2 px-md-4">

        {{-- Main Header --}}
        <div class="text-center mb-3 mb-md-4">
            <h1 class="fw-bolder fs-3 fs-md-1" style="color: var(--color-secondary);">
                <img src="{{ asset('images/group.png') }}" alt="Boxes Icon" class="me-2" style="width: 38px; height: auto;">User Management
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

                    <button type="button"
                        class="btn btn-action-primary btn-sm px-3 fw-bold w-md-auto"
                        data-bs-toggle="modal" data-bs-target="#createUserModal">
                        <i class="ti ti-user-plus me-2"></i>Add New User
                    </button>
                </div>

            </div>


            <div class="user-management-container">
                <!-- DESKTOP VIEW (Table Layout) -->
                @php
                    $isAdminOrChecker = in_array(strtolower(Auth::user()->role ?? ''), ['admin', 'checker']);
                    $colCount = $isAdminOrChecker ? 6 : 5;
                @endphp
                <div class="table-responsive d-none d-md-block">
                    <table class="table modern-table align-middle mb-0">
                        <thead>
                            <tr>
                                <!-- @if($isAdminOrChecker)
                                    <th class="col-check text-center">
                                        <input class="form-check-input" type="checkbox" id="selectAllUsers" title="Select all users">
                                    </th>
                                @endif -->
                                <th class="text-start">Name</th>
                                <th class="text-start">Email</th>
                                <th class="text-center">Role</th>
                                <th class="text-center">Warehouse</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
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
                                    <!-- @if($isAdminOrChecker)
                                        <td class="col-check text-center">
                                            <input class="form-check-input user-check" type="checkbox" value="{{ $user->id }}">
                                        </td>
                                    @endif -->
                                    <td class="text-start fw-bold">{{ $user->name }}</td>
                                    <td class="text-start text-muted">{{ $user->email }}</td>
                                    <td class="text-center">
                                        <span class="role-badge" style="background-color: {{ $roleColor }};">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="text-center">{{ $user->warehouse->name ?? '-' }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('user.edit', $user->id) }}"
                                                class="btn btn-outline-secondary btn-sm">
                                                <i class="ti ti-edit me-1"></i> Edit
                                            </a>
                                            <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    <i class="ti ti-trash me-1"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $colCount }}" class="text-center p-4">
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
                                    <span class="role-badge"
                                        style="background-color: {{ $roleColor }};">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </div>

                                <!-- Warehouse Info -->
                                <div class="small text-muted mb-2 mt-2">
                                    <i class="ti ti-building-warehouse me-1"></i>
                                    <span>Warehouse: {{ $user->warehouse->name ?? 'Unassigned' }}</span>
                                </div>

                                <!-- Actions Row -->
                                <div class="d-flex justify-content-end gap-2 mt-2 pt-2 border-top">
                                    <a href="{{ route('user.edit', $user->id) }}"
                                        class="btn btn-outline-secondary btn-sm py-1 px-2">
                                        <i class="ti ti-edit me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm py-1 px-2">
                                            <i class="ti ti-trash me-1"></i> Delete
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

    <!-- Create User Modal -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('user.store') }}" method="POST">
                    @csrf
                    <div class="modal-header text-white" style="background-color: var(--color-secondary);">
                        <h5 class="modal-title fw-bold" id="createUserModalLabel">
                            <i class="ti ti-user-plus me-2"></i>Add New User
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">

                        <div class="mb-3">
                            <label for="modal_user_name" class="form-label small fw-bold">Full Name</label>
                            <input type="text" name="name" id="modal_user_name" class="form-control" required placeholder="Full Name">
                        </div>

                        <div class="mb-3">
                            <label for="modal_user_email" class="form-label small fw-bold">Email Address</label>
                            <input type="email" name="email" id="modal_user_email" class="form-control" required placeholder="email@example.com">
                        </div>

                        <div class="mb-3">
                            <label for="modal_user_password" class="form-label small fw-bold">Password</label>
                            <input type="password" name="password" id="modal_user_password" class="form-control" required placeholder="Min. 8 characters">
                        </div>

                        <div class="mb-3">
                            <label for="modal_user_password_confirm" class="form-label small fw-bold">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="modal_user_password_confirm" class="form-control" required placeholder="Repeat password">
                        </div>

                        <div class="mb-3">
                            <label for="modal_user_role" class="form-label small fw-bold">Role</label>
                            <select name="role" id="modal_user_role" class="form-select" required onchange="toggleModalUserFields()">
                                <option value="" disabled selected>-- Select Role --</option>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                                <option value="driver">Driver</option>
                                <option value="checker">Checker</option>
                            </select>
                        </div>

                        {{-- Warehouse field (checker only) --}}
                        <div class="mb-3" id="modal_warehouse_field" style="display:none;">
                            <label for="modal_user_warehouse" class="form-label small fw-bold">Assign to Warehouse</label>
                            <select name="warehouse_id" id="modal_user_warehouse" class="form-select">
                                <option value="" disabled selected>-- Select Warehouse --</option>
                                @foreach($warehouses as $wh)
                                    <option value="{{ $wh->warehouse_id }}">{{ $wh->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Driver fields --}}
                        <div id="modal_driver_fields" style="display:none;">
                            <div class="mb-3">
                                <label for="modal_license_no" class="form-label small fw-bold">License Number</label>
                                <input type="text" name="license_no" id="modal_license_no" class="form-control" placeholder="License Number">
                            </div>
                            <div class="mb-3">
                                <label for="modal_driver_status" class="form-label small fw-bold">Driver Status</label>
                                <select name="status" id="modal_driver_status" class="form-select">
                                    <option value="Active" selected>Active</option>
                                    <option value="Suspended">Suspended</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-action-primary btn-sm px-4 fw-bold">
                            <i class="ti ti-device-floppy me-1"></i>Create Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleModalUserFields() {
            const role = document.getElementById('modal_user_role').value;
            document.getElementById('modal_warehouse_field').style.display = (role === 'checker') ? 'block' : 'none';
            document.getElementById('modal_driver_fields').style.display   = (role === 'driver')  ? 'block' : 'none';
        }

        // document.addEventListener('DOMContentLoaded', function() {
        //     const selectAll = document.getElementById('selectAllUsers');
        //     if (selectAll) {
        //         selectAll.addEventListener('change', function() {
        //             document.querySelectorAll('.user-check').forEach(cb => {
        //                 cb.checked = selectAll.checked;
        //             });
        //         });
        //     }
        // });
    </script>

@endsection