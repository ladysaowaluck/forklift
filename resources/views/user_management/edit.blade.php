@extends('layouts.app')

@section('content')
{{-- Ensure FontAwesome is loaded for icons --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
    :root {
        --color-primary: #E60012;
        --color-secondary: #004B8D;
        --color-background: #f4f6f9;
        --color-border: #e3e6f0;
        --border-radius-md: 0.75rem;
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
    #driver-fields, #password-section {
        border-top: 1px dashed var(--color-border);
        margin-top: 1.5rem;
        padding-top: 1.5rem;
    }
</style>

<div class="container-fluid py-4 px-lg-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <div class="card card-main-content">
                <div class="card-body p-4 p-lg-5">

                    {{-- Header --}}
                    <div class="text-center mb-5">
                        <h1 class="fw-bolder" style="color: var(--color-secondary);">
                            <i class="fas fa-user-edit me-2"></i>Edit User
                        </h1>
                        <p class="fs-5 text-muted">Update profile for: <span class="fw-bold">{{ $user->name }}</span></p>
                    </div>

                    <form action="{{ route('user.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Name</label>
                            <input type="text" id="name" name="name" class="form-control form-control-lg" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <input type="email" id="email" name="email" class="form-control form-control-lg" value="{{ old('email', $user->email) }}" required>
                        </div>

                        {{-- Password Section (NEW) --}}
                        <div id="password-section">
                             <h5 class="fw-bold mb-3" style="color: var(--color-secondary);">Change Password</h5>
                             <p class="text-muted small">Leave blank to keep the current password.</p>
                            <div class="mb-3">
                                <label for="password" class="form-label fw-bold">New Password</label>
                                <input type="password" id="password" name="password" class="form-control form-control-lg" autocomplete="new-password">
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label fw-bold">Confirm New Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control form-control-lg" autocomplete="new-password">
                            </div>
                        </div>


                        <div class="mb-3 mt-4">
                            <label for="role" class="form-label fw-bold">Role</label>
                            <select id="role" name="role" class="form-select form-select-lg" required>
                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="driver" {{ $user->role == 'driver' ? 'selected' : '' }}>Driver</option>
                                <option value="checker" {{ $user->role == 'checker' ? 'selected' : '' }}>Checker</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="warehouse_id" class="form-label fw-bold">Assigned Warehouse</label>
                            <select id="warehouse_id" name="warehouse_id" class="form-select form-select-lg">
                                <option value="">Select Warehouse</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->warehouse_id }}" 
                                        {{ $user->warehouse_id == $warehouse->warehouse_id ? 'selected' : '' }}>
                                        {{ $warehouse->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Driver-specific fields, hidden by default --}}
                        <div id="driver-fields" style="display: none;">
                             <h5 class="fw-bold mb-3" style="color: var(--color-secondary);">Driver Information</h5>
                            <div class="mb-3">
                                <label for="license_no" class="form-label fw-bold">License Number</label>
                                <input type="text" id="license_no" name="license_no" class="form-control form-control-lg" 
                                       value="{{ old('license_no', optional($user->driver)->license_no) }}">
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label fw-bold">Status</label>
                                <select id="status" name="status" class="form-select form-select-lg">
                                    <option value="Active" {{ optional($user->driver)->status == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Suspended" {{ optional($user->driver)->status == 'Suspended' ? 'selected' : '' }}>Suspended</option>
                                    <option value="Inactive" {{ optional($user->driver)->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-lg text-white" style="background-color: var(--color-primary);">
                                <i class="fas fa-save me-2"></i>Update User
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <a href="{{ route('user.management') }}" class="btn btn-link text-secondary">Back to User List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const roleSelect = document.getElementById('role');
        const driverFields = document.getElementById('driver-fields');

        function toggleDriverFields() {
            if (roleSelect.value === 'driver') {
                driverFields.style.display = 'block';
            } else {
                driverFields.style.display = 'none';
            }
        }

        toggleDriverFields();

        roleSelect.addEventListener('change', toggleDriverFields);
    });
</script>
@endsection
