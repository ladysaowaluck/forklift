@extends('layouts.app')

@section('content')
{{-- Ensure FontAwesome is loaded for icons --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
    :root {
        --color-primary: #ffffff;
        --color-secondary: #004B8D;
        --color-background: #f4f6f9;
        --color-border: #e3e6f0;
        --border-radius-md: 0.75rem;
    }
    body {
        background-color: var(--color-background);
        color: #212529;
        font-size: clamp(0.875rem, 1.5vw, 1rem);
    }
    .card-main-content {
        background-color: #ffffff;
        border: 1px solid var(--color-border);
        box-shadow: 0 4px 18px rgba(0,0,0,0.05);
        border-radius: var(--border-radius-md);
    }
    #driver-fields, #password-section {
        border-top: 1px dashed var(--color-border);
        margin-top: 1rem;
        padding-top: 1rem;
    }

    @media (min-width: 768px) {
        #driver-fields, #password-section {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
        }
    }
</style>

<div class="container-fluid py-3 py-md-4 px-2 px-md-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-xl-6">

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


            <div class="card card-main-content border-0">
                <div class="card-body p-3 p-sm-4 p-lg-5">

                    <div class="text-center mb-3 mb-md-4">
                        <h1 class="fw-bolder fs-3 fs-md-2" style="color: var(--color-secondary);">
                            <i class="fas fa-user-edit me-2"></i>Edit User
                        </h1>
                        <p class="fs-6 fs-md-5 text-muted mb-0">Update profile for: <span class="fw-bold">{{ $user->name }}</span></p>
                    </div>

                    <form action="{{ route('user.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-2 g-md-3">
                            <div class="col-12 col-md-6">
                                <label for="name" class="form-label small fw-bold">Name</label>
                                <input type="text" id="name" name="name" class="form-control form-control-sm form-control-md-normal" value="{{ old('name', $user->name) }}" required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="email" class="form-label small fw-bold">Email</label>
                                <input type="email" id="email" name="email" class="form-control form-control-sm form-control-md-normal" value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>

                        {{-- Password Section (NEW) --}}
                        <div id="password-section">
                             <h5 class="fw-bold mb-1 fs-6 fs-md-5" style="color: var(--color-secondary);">Change Password</h5>
                             <p class="text-muted small mb-3">Leave blank to keep the current password.</p>
                             
                            <div class="row g-2 g-md-3">
                                <div class="col-12 col-md-6">
                                    <label for="password" class="form-label small fw-bold">New Password</label>
                                    <input type="password" id="password" name="password" class="form-control form-control-sm form-control-md-normal" autocomplete="new-password">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="password_confirmation" class="form-label small fw-bold">Confirm New Password</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control form-control-sm form-control-md-normal" autocomplete="new-password">
                                </div>
                            </div>
                        </div>

                        <div class="row g-2 g-md-3 mt-1 mt-md-2">
                            <div class="col-12 col-md-6">
                                <label for="role" class="form-label small fw-bold">Role</label>
                                <select id="role" name="role" class="form-select form-select-sm form-select-md-normal" required>
                                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="driver" {{ $user->role == 'driver' ? 'selected' : '' }}>Driver</option>
                                    <option value="checker" {{ $user->role == 'checker' ? 'selected' : '' }}>Checker</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="warehouse_id" class="form-label small fw-bold">Assigned Warehouse</label>
                                <select id="warehouse_id" name="warehouse_id" class="form-select form-select-sm form-select-md-normal">
                                    <option value="">Select Warehouse</option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->warehouse_id }}" 
                                            {{ $user->warehouse_id == $warehouse->warehouse_id ? 'selected' : '' }}>
                                            {{ $warehouse->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Driver-specific fields, hidden by default --}}
                        <div id="driver-fields" style="display: none;">
                            <h5 class="fw-bold mb-3 fs-6 fs-md-5" style="color: var(--color-secondary);">Driver Information</h5>
                            
                            <div class="row g-2 g-md-3">
                                <div class="col-12 col-md-6">
                                    <label for="license_no" class="form-label small fw-bold">License Number</label>
                                    <input type="text" id="license_no" name="license_no" class="form-control form-control-sm form-control-md-normal" 
                                           value="{{ old('license_no', optional($user->driver)->license_no) }}">
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="status" class="form-label small fw-bold">Status</label>
                                    <select id="status" name="status" class="form-select form-select-sm form-select-md-normal">
                                        <option value="Active" {{ optional($user->driver)->status == 'Active' ? 'selected' : '' }}>Active</option>
                                        <option value="Suspended" {{ optional($user->driver)->status == 'Suspended' ? 'selected' : '' }}>Suspended</option>
                                        <option value="Inactive" {{ optional($user->driver)->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="d-grid gap-2 mt-3 mt-md-4">
                            <button type="submit" class="btn btn-primary btn-sm btn-md-normal text-white fw-bold py-2" 
                                style="background-color: var(--color-primary); border-color: var(--color-primary);">
                                <i class="fas fa-save me-2"></i>Update User
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-3 mt-md-4">
                        <a href="{{ route('user.management') }}" class="btn btn-link btn-sm text-secondary text-decoration-none">
                            <i class="fas fa-arrow-left me-1"></i>Back to User List
                        </a>
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